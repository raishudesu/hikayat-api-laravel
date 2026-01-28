<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\Eloquent\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepository $userRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function createUser(array $userData)
    {
        $userData['password'] = Hash::make($userData['password']);

        return $this->userRepository->create($userData);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->getByEmail($email);
    }
}
