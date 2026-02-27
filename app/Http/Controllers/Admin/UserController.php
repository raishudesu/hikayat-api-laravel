<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserRequest $request)
    {
        $users = $this->userService->getUsersPaginated($request->query('page'), $request->query('per_page', 20));

        return UserResource::collection($users)
            ->additional([
                'message' => 'Users retrieved successfully.',
            ]);
    }

    public function show(User $user)
    {
        return UserResource::make($user)
            ->additional([
                'message' => 'User retrieved successfully.',
            ]);
    }

    public function update(UserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $this->userService->updateUser($user->uuid, $request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
        ], 200);
    }

    public function updatePassword(UserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $this->userService->updateUser($user->uuid, $request->validated());

        return response()->json([
            'message' => 'User password updated successfully.',
        ], 200);
    }
}
