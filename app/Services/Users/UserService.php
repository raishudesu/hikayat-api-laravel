<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(UserRepositoryInterface $userRepository)
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

    public function getUsersPaginated(?int $page, int $perPage = 20): LengthAwarePaginator
    {
        return $this->userRepository->paginate($page, $perPage);
    }

    public function getUserByUuid(string $uuid): ?User
    {
        return $this->userRepository->getByUuidOrFail($uuid);
    }

    public function updateUser(string $uuid, array $userData): void
    {
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $this->userRepository->update($uuid, $userData);
    }

    public function followUser(string $followerUuid, string $followingUuid): void
    {
        if ($followerUuid === $followingUuid) {
            throw new \Exception('You cannot follow yourself.');
        }

        $followingUser = $this->userRepository->getByUuidOrFail($followingUuid);
        $followerUser = $this->userRepository->getByUuidOrFail($followerUuid);

        $followingUser->followers()->syncWithoutDetaching($followerUser);
    }

    public function unfollowUser(string $followerUuid, string $followingUuid): void
    {
        $followingUser = $this->userRepository->getByUuidOrFail($followingUuid);
        $followerUser = $this->userRepository->getByUuidOrFail($followerUuid);

        $followingUser->followers()->detach($followerUser);
    }
}
