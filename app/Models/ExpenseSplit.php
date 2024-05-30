<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseSplit extends Model
{
    protected $fillable = [
        'expense_id',
        'group_id',
        'paid_user_id',
        'receive_user_id',
        'amount',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function paidUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_user_id');
    }

    public function receiveUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receive_user_id');
    }
}
