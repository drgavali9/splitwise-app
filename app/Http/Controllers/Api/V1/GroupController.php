<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GroupRequest;
use App\Http\Resources\Api\V1\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        ////        $this->authorize('viewAny', Group::class);
        $groups = Group::query()
            ->whereHas('users', function ($q) {
                $q->where('id', auth()->id());
            })
            ->get();

        return GroupResource::collection($groups);
    }

    public function store(GroupRequest $request)
    {
        //        $this->authorize('create', Group::class);

        return GroupResource::make($request->persist($request->validated()));
    }

    public function show(Group $group)
    {
        //        $this->authorize('view', $group);

        return GroupResource::make($group->load('users'));
    }

    public function update(GroupRequest $request, Group $group)
    {
        //        $this->authorize('update', $group);

        $group->update($request->validated());

        return GroupResource::make($group);
    }

    public function destroy(Group $group)
    {
        //        $this->authorize('delete', $group);

        $group->delete();

        return response()->json(['message' => 'Group delete successfully']);
    }

    public function addUser(Group $group, User $user)
    {
        if ($group->owner_id != auth()->id()) {
            ValidationException::withMessages(['message' => 'Unauthorized access...']);
        }

        $group->users()->attach($user->id);

        return GroupResource::make($group->loadMissing('users'));
    }
}
