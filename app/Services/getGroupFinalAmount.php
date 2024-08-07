<?php

namespace App\Services;

use App\Models\ExpenseSplit;
use App\Traits\Makeable;
use Illuminate\Support\Facades\DB;

class getGroupFinalAmount
{
    use Makeable;

    public array $finalTransactions;

    public int $userId;

    public int $groupId;

    public function __construct(int $userId, int $groupId)
    {
        $this->userId = $userId;
        $this->groupId = $groupId;

        $this->finalTransactions = $this->computeFinalTransactions();
    }

    protected function computeFinalTransactions(): array
    {
        $transactions = $this->getTransactions();
        $balances = $this->calculateNetBalances($transactions);

        return $this->combineDebts($balances);
    }

    protected function getTransactions(): array
    {
        return ExpenseSplit::select('party_unique_id', DB::raw('SUM(amount) as total_amount'))
            ->where('group_id', $this->groupId)
            ->groupBy('party_unique_id')
            ->get()
            ->map(function ($transaction) {
                [$groupId, $paidUserId, $receivedUserId] = explode('-', $transaction->party_unique_id);

                return [
                    'paid_by' => $paidUserId,
                    'received_by' => $receivedUserId,
                    'amount' => $transaction->total_amount,
                ];
            })
            ->toArray();
    }

    protected function calculateNetBalances(array $transactions): array
    {
        $balances = [];

        foreach ($transactions as $transaction) {
            $paidBy = $transaction['paid_by'];
            $receivedBy = $transaction['received_by'];
            $amount = round($transaction['amount'], 2);

            $balances[$paidBy] = ($balances[$paidBy] ?? 0) - $amount;
            $balances[$receivedBy] = ($balances[$receivedBy] ?? 0) + $amount;
        }

        return array_map(function ($number) {
            return round($number, 2);
        }, array_filter($balances));
    }

    protected function combineDebts(array $balances): array
    {
        $finalTransactions = [];

        while (! empty($balances)) {
            $maxCreditor = array_keys($balances, max($balances))[0];
            $maxDebtor = array_keys($balances, min($balances))[0];

            $amount = round(min($balances[$maxCreditor], -$balances[$maxDebtor]), 2);

            $finalTransactions[] = [
                'paid_by' => $maxDebtor,
                'received_by' => $maxCreditor,
                'amount' => $amount,
            ];

            $balances[$maxCreditor] = round($balances[$maxCreditor] - $amount, 2);
            $balances[$maxDebtor] = round($balances[$maxDebtor] + $amount, 2);

            if (data_get($balances, $maxCreditor) == 0) {
                unset($balances[$maxCreditor]);
            }
            if (data_get($balances, $maxDebtor) == 0) {
                unset($balances[$maxDebtor]);
            }
        }

        return $finalTransactions;
    }

    public function getAmount(): array
    {
        $memberWiseAmounts = [];

        foreach ($this->finalTransactions as $transaction) {
            $paidBy = $transaction['paid_by'];
            $receivedBy = $transaction['received_by'];
            $amount = $transaction['amount'];

            if ($amount > 0) {
                $memberWiseAmounts[$paidBy]['owes'][] = ['user' => $receivedBy, 'amount' => $amount];
                $memberWiseAmounts[$receivedBy]['owe'][] = ['user' => $paidBy, 'amount' => $amount];
            }
        }

        return $memberWiseAmounts[$this->userId] ?? [];
    }
}
