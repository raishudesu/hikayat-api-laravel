<?php

namespace App\Http\Resources\Posts;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'content' => $this->content,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => UserResource::make($this->whenLoaded('user')),
            // 'parent' => PostResource::make($this->whenLoaded('parent')),
            // 'reposts' => PostResource::collection($this->whenLoaded('reposts')),
            // 'comments' => CommentResource::collection($this->whenLoaded('comments')),
            // 'reports' => ReportResource::collection($this->whenLoaded('reports')),
            // 'interactions' => InteractionResource::collection($this->whenLoaded('interactions')),
        ];
    }
}
