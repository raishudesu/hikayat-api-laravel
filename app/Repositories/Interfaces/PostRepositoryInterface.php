<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getByUuidOrFail(string $uuid): Post;
}
