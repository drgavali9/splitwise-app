<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        
    }

    public function view(User $user, Group $group)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, Group $group)
    {
    }

    public function delete(User $user, Group $group)
    {
    }

    public function restore(User $user, Group $group)
    {
    }

    public function forceDelete(User $user, Group $group)
    {
    }
}
