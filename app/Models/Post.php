<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $parent_id
 * @property string $title
 * @property string $content
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'user_id',
        'parent_id',
        'title',
        'content',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the original post that was reposted.
     */
    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    /**
     * Get all reposts of this post.
     */
    public function reposts()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    /**
     * Get all comments for this post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all reports for this post.
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class, 'interactable_id');
    }
}
