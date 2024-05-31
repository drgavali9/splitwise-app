<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        return new GroupResource($request->persist($request->validated()));
    }

    public function show(Group $group)
    {
        //        $this->authorize('view', $group);

        return new GroupResource($group);
    }

    public function update(GroupRequest $request, Group $group)
    {
        //        $this->authorize('update', $group);

        $group->update($request->validated());

        return new GroupResource($group);
    }

    public function destroy(Group $group)
    {
        //        $this->authorize('delete', $group);

        $group->delete();

        return response()->json(['message' => 'Group delete successfully']);
    }
}
