<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Expense $expense): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Expense $expense): bool
    {
    }

    public function delete(User $user, Expense $expense): bool
    {
    }

    public function restore(User $user, Expense $expense): bool
    {
    }

    public function forceDelete(User $user, Expense $expense): bool
    {
    }
}
