<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): ?User;

    public function create(array $userData): User;
}
