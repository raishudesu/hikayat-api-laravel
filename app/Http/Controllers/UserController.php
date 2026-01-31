<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Hash;

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

        return UserResource::collection($users)->additional([
            'message' => 'Users retrieved successfully.',
        ]);
    }

    public function show(UserRequest $request, string $uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);
        if ($user) {
            return UserResource::make($user)->additional([
                'message' => 'User retrieved successfully.',
            ]);
        }

        return response()->json([
            'message' => 'User not found.',
        ], 404);
    }

    public function update(UserRequest $request, string $uuid)
    {
        $sessionUser = $request->user();

        $user = $this->userService->getUserByUuid($uuid);
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }


        // TODO: If there are user roles for authorization is available, authorize if the 
        // user is an ADMIN or the user is updating their own profile
        if ($user->id != $sessionUser->id) {
            return response()->json([
                'message' => 'You are not authorized to update this user.',
            ], 403);
        }

        $this->userService->updateUser($uuid, $request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
        ], 200);
    }

    public function updatePassword(UserRequest $request, string $uuid)
    {
        $requestData = $request->validated();
        $sessionUser = $request->user();

        $user = $this->userService->getUserByUuid($uuid);
        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        // TODO: If there are user roles for authorization is available, authorize if the 
        // user is an ADMIN or the user is updating their own profile
        if ($user->id != $sessionUser->id) {
            return response()->json([
                'message' => 'You are not authorized to update this user.',
            ], 403);
        }

        if (password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'You cannot use your old password',
            ], 400);
        }

        if (isset($requestData['password'])) {
            $requestData['password'] = Hash::make($requestData['password']);
        }

        $this->userService->updateUser($uuid, $requestData);

        return response()->json([
            'message' => 'User password updated successfully.',
        ], 200);
    }
}
