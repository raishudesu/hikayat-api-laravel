<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\Users\UserFollowRequest;
use App\Http\Resources\UserResource;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(User $user)
    {
        return UserResource::make($user)->additional([
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

    public function updatePassword(UserRequest $request)
    {
        $user = $request->user();

        Gate::authorize('update', $user);

        $this->userService->updateUser($user->uuid, $request->validated());

        return response()->json([
            'message' => 'User password updated successfully.',
        ], 200);
    }

    public function followUser(UserFollowRequest $request)
    {
        $user = $request->user();

        $this->userService->followUser($user->uuid, $request->validated('following_id'));

        return response()->json([
            'message' => 'User followed successfully.',
        ], 200);
    }

    public function unfollowUser(UserFollowRequest $request)
    {
        $user = $request->user();

        $this->userService->unfollowUser($user->uuid, $request->validated('following_id'));

        return response()->json([
            'message' => 'User unfollowed successfully.',
        ], 200);
    }
}
