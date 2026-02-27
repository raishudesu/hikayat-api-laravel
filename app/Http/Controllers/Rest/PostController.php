<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostRequest;
use App\Http\Resources\Posts\PostResource;
use App\Models\Post;
use App\Services\Posts\PostService;

class PostController extends Controller
{

    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(PostRequest $request)
    {
        return PostResource::collection($this->postService->paginate($request->query('page'), $request->query("per_page", 20)));
    }

    public function show(Post $post)
    {
        return PostResource::make($post)
            ->additional([
                'message' => 'Post retrieved successfully.',
            ]);
    }
}
