<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    const LIKE = "like";
    const REPOST = "repost";

    const UPDATED_AT = null;

    protected $fillable = [
        "type" => "enum:like,repost",
    ];

    public function likes()
    {
        return $this->where("type", self::LIKE);
    }

    public function reposts()
    {
        return $this->where("type", self::REPOST);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, "interactable_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function casts()
    {
        return [
            "created_at" => "datetime",
        ];
    }
}
