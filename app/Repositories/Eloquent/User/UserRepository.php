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
}
