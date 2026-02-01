<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $userData): User
    {
        return User::create($userData);
    }

    public function paginate(?int $page = null, int $perPage = 20): LengthAwarePaginator
    {
        return User::paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function getByUuid(string $uuid): ?User
    {
        return User::where('uuid', $uuid)->first();
    }

    public function getByUuidOrFail(string $uuid): User
    {
        return User::where('uuid', $uuid)->firstOrFail();
    }


    public function update(string $uuid, array $userData): void
    {
        User::where('uuid', $uuid)->update($userData);
    }
}
