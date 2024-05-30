<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'group_id',
        'paid_user_id',
        'description',
        'amount',
        'split_type',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function paidUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_user_id');
    }
}
