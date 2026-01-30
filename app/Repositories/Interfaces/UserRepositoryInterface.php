<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): ?User;

    public function create(array $userData): User;

    public function paginate(?int $page = null, int $perPage = 20): LengthAwarePaginator;
}
