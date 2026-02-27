<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function paginate(?int $page, int $perPage): LengthAwarePaginator;

    public function create(array $data): Post;

    public function update(string $uuid, array $postData): void;

    public function delete(string $uuid): void;

    public function getPaginatedPostsByUserId(?int $page, int $perPage, int $userId): LengthAwarePaginator;
}
