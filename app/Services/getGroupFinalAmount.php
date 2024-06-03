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

        $transactions = $this->getTransactions();
        $balances = $this->CalculateNetBalances($transactions);
        $this->finalTransactions = $this->CombineDebts($balances);
    }

    public function getTransactions(): array
    {
        return ExpenseSplit::select('party_unique_id', DB::raw('SUM(amount) as total_amount'))
            ->where('group_id', $this->groupId)
            ->groupBy('party_unique_id')
            ->get()
            ->map(function ($transaction) {
                [$groupId, $paidUserId, $receivedUserId] = explode('-', $transaction->party_unique_id);

                return ['paid_by' => $paidUserId, 'received_by' => $receivedUserId, 'amount' => $transaction->total_amount];
            })
            ->toArray();
    }

    public function CalculateNetBalances(array $transactions = []): array
    {
        $balances = [];

        foreach ($transactions as $transaction) {
            $paidBy = $transaction['paid_by'];
            $receivedBy = $transaction['received_by'];
            $amount = $transaction['amount'];

            // Adjust balances directly without initializing to zero first
            $balances[$paidBy] = ($balances[$paidBy] ?? 0) - $amount;
            $balances[$receivedBy] = ($balances[$receivedBy] ?? 0) + $amount;
        }

        // Filter out zero balances
        return array_filter($balances);
    }

    public function CombineDebts(array $balances = []): array
    {

        // Step 2: Combine debts
        $finalTransactions = [];

        while (! empty($balances)) {
            // Find the person with the maximum positive balance (creditor)
            $maxCreditor = array_keys($balances, max($balances))[0];
            // Find the person with the maximum negative balance (debtor)
            $maxDebtor = array_keys($balances, min($balances))[0];

            // Determine the amount to be settled
            $amount = min($balances[$maxCreditor], -$balances[$maxDebtor]);

            // Record the transaction
            $finalTransactions[] = ['paid_by' => $maxDebtor, 'received_by' => $maxCreditor, 'amount' => $amount];

            // Update the balances
            $balances[$maxCreditor] -= $amount;
            $balances[$maxDebtor] += $amount;

            // Remove zero balances
            if ($balances[$maxCreditor] == 0) {
                unset($balances[$maxCreditor]);
            }

            if ($balances[$maxDebtor] == 0) {
                unset($balances[$maxDebtor]);
            }
        }

        return $finalTransactions;
    }

    public function getAmount(): array
    {
        // Step 3: Generate member-wise final amounts
        $memberWiseAmounts = [];

        foreach ($this->finalTransactions as $transaction) {
            $paidBy = $transaction['paid_by'];
            $receivedBy = $transaction['received_by'];
            $amount = $transaction['amount'];

            if (! isset($memberWiseAmounts[$paidBy])) {
                $memberWiseAmounts[$paidBy] = ['owes' => [], 'owe' => []];
            }

            if (! isset($memberWiseAmounts[$receivedBy])) {
                $memberWiseAmounts[$receivedBy] = ['owes' => [], 'owe' => []];
            }

            $memberWiseAmounts[$paidBy]['owes'][] = ['user' => $receivedBy, 'amount' => $amount];
            $memberWiseAmounts[$receivedBy]['owe'][] = ['user' => $paidBy, 'amount' => $amount];
        }

        return data_get($memberWiseAmounts, $this->userId);
    }
}
