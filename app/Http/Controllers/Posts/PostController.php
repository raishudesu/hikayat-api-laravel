<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\PostRequest;
use App\Http\Resources\Posts\PostResource;
use App\Models\Post;
use App\Services\Posts\PostService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(PostRequest $request): AnonymousResourceCollection
    {
        return PostResource::collection($this->postService->paginate($request->query('page'), $request->query("per_page", 20)));
    }

    public function show(Post $post): PostResource
    {
        return PostResource::make($post)
            ->additional([
                'message' => 'Post retrieved successfully.',
            ]);
    }

    public function store(CreatePostRequest $request): Post
    {
        return $this->postService->create($request->validated());
    }
}
