<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $search = request()->get('search');
        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })
            ->where('id', '<>', auth()->id())
            ->paginate(request()->get('perPage', 20));

        return UserResource::collection($users);
    }

    public function user()
    {
        return UserResource::make(auth()->user());
    }
}
