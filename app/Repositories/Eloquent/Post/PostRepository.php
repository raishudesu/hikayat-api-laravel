<?php

namespace App\Repositories\Eloquent\Post;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    public function getByUuidOrFail(string $uuid): Post
    {
        return Post::where('uuid', $uuid)->firstOrFail();
    }
}
