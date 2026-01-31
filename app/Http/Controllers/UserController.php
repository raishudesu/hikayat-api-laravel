<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Users\UserService;

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
}
