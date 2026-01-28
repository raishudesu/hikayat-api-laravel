<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->userService->createUser($validated);

        return response()->json([
            "success" => true,
            'message' => 'User created successfully.',
        ], 201);
    }
}
