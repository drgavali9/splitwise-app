<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'paid_user_id',
        'description',
        'amount',
        'split_type',
    ];

    protected static function booted()
    {
        static::created(function (self $expense) {
            self::addExpenses($expense);
        });

        static::updating(function (self $expense) {
            if ($expense->isDirty('paid_user_id') || $expense->isDirty('amount') || $expense->isDirty('split_type')) {
                $expense->expenseSplits()->delete();
                self::addExpenses($expense);
            }
        });

        static::deleting(function (self $expense) {
            $expense->expenseSplits()->delete();
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function paidUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_user_id');
    }

    public function expenseSplits(): HasMany
    {
        return $this->hasMany(ExpenseSplit::class, 'expense_id');
    }

    public static function addExpenses(Expense $expense, array $splitData = []): void
    {
        $users = $expense->group->users;

        $splitExpenses = [];

        $now = now();

        if ($expense->split_type == 1) {
            $amount = round($expense->amount / count($users), 2);
            foreach ($users as $user) {
                $splitExpenses[] = [
                    'expense_id'   => $expense->id,
                    'group_id'     => $expense->group_id,
                    'paid_user_id' => $expense->paid_user_id,
                    'receive_user_id' => $user->id,
                    'amount'       => $amount,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        } elseif (! empty($splitData) && $expense->split_type == 2) {
            foreach ($splitData as $split) {
                $splitExpenses[] = [
                    'expense_id'      => $expense->id,
                    'group_id'        => $expense->group_id,
                    'paid_user_id'    => $expense->paid_user_id,
                    'receive_user_id' => $split['user_id'],
                    'amount'          => round($split['amount'], 2),
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        $expense->expenseSplits()->insert($splitExpenses);
    }
}
