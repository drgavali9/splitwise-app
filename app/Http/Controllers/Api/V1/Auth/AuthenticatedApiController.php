<?php

namespace App\Http\Controllers\Api\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuthenticatedApiController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $tenant = User::create($request->validated());

        return UserResource::make($tenant)
            ->additional(['token' => $tenant->createToken('auth_token')->plainTextToken]);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(Arr::only($request->validated(), ['email', 'password']))) {
            $tenant = Auth::user();

            return UserResource::make($tenant)
                ->additional(['token' => $tenant->createToken('auth_token')->plainTextToken]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
