<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'hiker_id',
        'guide_id',
        'status',
        'hike_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hike_date' => 'date',
            'status' => BookingStatus::class,
        ];
    }

    public function hiker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hiker_id');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
}
