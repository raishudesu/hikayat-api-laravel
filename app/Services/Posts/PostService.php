<?php

namespace App\Services\Posts;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

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
}
