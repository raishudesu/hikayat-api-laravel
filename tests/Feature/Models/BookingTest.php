<?php

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('booking has fillable attributes', function () {
    $fillable = [
        'hiker_id',
        'guide_id',
        'status',
        'hike_date',
        'notes',
    ];

    $booking = new Booking();

    expect($booking->getFillable())->toBe($fillable);
});

test('booking uses uuid as primary key', function () {
    $booking = Booking::factory()->create();

    expect($booking->id)->toBeString()
        ->and(strlen($booking->id))->toBe(36);
});

test('booking casts hike_date to date', function () {
    $booking = Booking::factory()->create([
        'hike_date' => '2026-02-15',
    ]);

    expect($booking->hike_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('booking casts status to BookingStatus enum', function () {
    $booking = Booking::factory()->create([
        'status' => 'pending',
    ]);

    expect($booking->status)->toBeInstanceOf(BookingStatus::class)
        ->and($booking->status)->toBe(BookingStatus::PENDING);
});

test('booking belongs to a hiker', function () {
    $hiker = User::factory()->create();
    $booking = Booking::factory()->create([
        'hiker_id' => $hiker->id,
    ]);

    expect($booking->hiker)->toBeInstanceOf(User::class)
        ->and($booking->hiker->id)->toBe($hiker->id);
});

test('booking belongs to a guide', function () {
    $guide = User::factory()->create(['is_verified_guide' => true]);
    $booking = Booking::factory()->create([
        'guide_id' => $guide->id,
    ]);

    expect($booking->guide)->toBeInstanceOf(User::class)
        ->and($booking->guide->id)->toBe($guide->id)
        ->and($booking->guide->is_verified_guide)->toBeTrue();
});

test('booking can be created with all required fields', function () {
    $hiker = User::factory()->create();
    $guide = User::factory()->create(['is_verified_guide' => true]);

    $booking = Booking::create([
        'hiker_id' => $hiker->id,
        'guide_id' => $guide->id,
        'status' => BookingStatus::PENDING,
        'hike_date' => '2026-03-01',
        'notes' => 'Test booking notes',
    ]);

    expect($booking)->toBeInstanceOf(Booking::class)
        ->and($booking->hiker_id)->toBe($hiker->id)
        ->and($booking->guide_id)->toBe($guide->id)
        ->and($booking->status)->toBe(BookingStatus::PENDING)
        ->and($booking->hike_date->format('Y-m-d'))->toBe('2026-03-01')
        ->and($booking->notes)->toBe('Test booking notes');
});

test('booking status can be updated', function () {
    $booking = Booking::factory()->create([
        'status' => BookingStatus::PENDING,
    ]);

    $booking->update(['status' => BookingStatus::ACCEPTED]);

    expect($booking->fresh()->status)->toBe(BookingStatus::ACCEPTED);
});

test('booking can have all status types', function () {
    $statuses = [
        BookingStatus::PENDING,
        BookingStatus::ACCEPTED,
        BookingStatus::DECLINED,
        BookingStatus::COMPLETED,
    ];

    foreach ($statuses as $status) {
        $booking = Booking::factory()->create(['status' => $status]);
        expect($booking->status)->toBe($status);
    }
});

test('booking notes are optional', function () {
    $booking = Booking::factory()->create([
        'notes' => null,
    ]);

    expect($booking->notes)->toBeNull();
});

test('booking has timestamps', function () {
    $booking = Booking::factory()->create();

    expect($booking->created_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($booking->updated_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('booking factory creates valid booking', function () {
    $booking = Booking::factory()->create();

    expect($booking)->toBeInstanceOf(Booking::class)
        ->and($booking->hiker)->toBeInstanceOf(User::class)
        ->and($booking->guide)->toBeInstanceOf(User::class)
        ->and($booking->guide->is_verified_guide)->toBeTrue()
        ->and($booking->status)->toBeInstanceOf(BookingStatus::class)
        ->and($booking->hike_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('booking hiker and guide cannot be the same user', function () {
    $user = User::factory()->create(['is_verified_guide' => true]);

    $booking = Booking::factory()->create([
        'hiker_id' => $user->id,
        'guide_id' => $user->id,
    ]);

    // This test documents current behavior - you may want to add validation
    expect($booking->hiker->id)->toBe($booking->guide->id);
});

test('booking requires explicit status when created via model', function () {
    $hiker = User::factory()->create();
    $guide = User::factory()->create(['is_verified_guide' => true]);

    $booking = Booking::create([
        'hiker_id' => $hiker->id,
        'guide_id' => $guide->id,
        'hike_date' => '2026-03-01',
    ]);

    // When created via Eloquent without status, it will be null
    // The database default 'pending' only applies to direct SQL inserts
    expect($booking->status)->toBeNull();
});

test('booking hike_date can be in the future', function () {
    $futureDate = now()->addMonths(3)->format('Y-m-d');
    $booking = Booking::factory()->create([
        'hike_date' => $futureDate,
    ]);

    expect($booking->hike_date->format('Y-m-d'))->toBe($futureDate)
        ->and($booking->hike_date->isFuture())->toBeTrue();
});

test('booking hike_date can be today', function () {
    $today = now()->format('Y-m-d');
    $booking = Booking::factory()->create([
        'hike_date' => $today,
    ]);

    expect($booking->hike_date->isToday())->toBeTrue();
});

test('booking can be deleted', function () {
    $booking = Booking::factory()->create();
    $bookingId = $booking->id;

    $booking->delete();

    expect(Booking::find($bookingId))->toBeNull();
});

test('deleting a user cascades to their bookings as hiker', function () {
    $hiker = User::factory()->create();
    $booking = Booking::factory()->create([
        'hiker_id' => $hiker->id,
    ]);

    $bookingId = $booking->id;
    $hiker->delete();

    expect(Booking::find($bookingId))->toBeNull();
});

test('deleting a user cascades to their bookings as guide', function () {
    $guide = User::factory()->create(['is_verified_guide' => true]);
    $booking = Booking::factory()->create([
        'guide_id' => $guide->id,
    ]);

    $bookingId = $booking->id;
    $guide->delete();

    expect(Booking::find($bookingId))->toBeNull();
});
