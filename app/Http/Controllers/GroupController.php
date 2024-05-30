<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Group::class);

        return GroupResource::collection(Group::all());
    }

    public function store(GroupRequest $request)
    {
        $this->authorize('create', Group::class);

        return new GroupResource(Group::create($request->validated()));
    }

    public function show(Group $group)
    {
        $this->authorize('view', $group);

        return new GroupResource($group);
    }

    public function update(GroupRequest $request, Group $group)
    {
        $this->authorize('update', $group);

        $group->update($request->validated());

        return new GroupResource($group);
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return response()->json();
    }
}
