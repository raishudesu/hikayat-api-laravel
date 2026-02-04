<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function show(Post $post)
    {
        return PostResource::make($post)
            ->additional([
                'message' => 'Post retrieved successfully.',
            ]);
    }
}
