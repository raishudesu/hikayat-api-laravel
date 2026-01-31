<?php

namespace App\Models;

use App\Enums\InteractionType;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{

    const UPDATED_AT = null;

    protected $fillable = [
        "user_id",
        "interactable_id",
        "type",
    ];

    protected function casts(): array
    {
        return [
            "created_at" => "datetime",
            "type" => InteractionType::class,
        ];
    }

    public function scopeLikes($query)
    {
        return $query->where("type", InteractionType::LIKE);
    }

    public function scopeReposts($query)
    {
        return $query->where("type", InteractionType::REPOST);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, "interactable_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
