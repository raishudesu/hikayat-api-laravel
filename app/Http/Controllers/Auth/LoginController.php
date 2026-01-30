<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->userService->getUserByEmail($validated['email']);
        if (empty($user)) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        if (! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials.',
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->toResource(),
        ]);
    }
}
