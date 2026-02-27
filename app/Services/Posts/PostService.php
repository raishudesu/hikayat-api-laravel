<?php

namespace App\Services\Posts;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    protected PostRepositoryInterface $postRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function paginate(?int $page, int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->paginate($page, $perPage);
    }

    public function create(array $data): Post
    {
        return $this->postRepository->create($data);
    }

    public function update(string $uuid, array $postData): void
    {
        $this->postRepository->update($uuid, $postData);
    }

    public function getPostsByUser(?int $page, int $perPage, int $userId): LengthAwarePaginator
    {
        return $this->postRepository->getPaginatedPostsByUserId($page, $perPage, $userId);
    }
}
