<?php

namespace App\Repositories\Eloquent\Post;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{

    public function paginate(?int $page, int $perPage): LengthAwarePaginator
    {
        return Post::with('user')->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(string $uuid, array $postData): void
    {
        Post::where('uuid', $uuid)->update($postData);
    }

    public function delete(string $uuid): void
    {
        Post::where('uuid', $uuid)->delete();
    }

    public function getPaginatedPostsByUserId(?int $page, int $perPage, int $userId): LengthAwarePaginator
    {
        return Post::where('user_id', $userId)->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }
}
