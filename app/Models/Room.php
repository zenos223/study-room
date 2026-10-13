<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'building', 'location', 'floor', 'capacity', 'category', 'description', 'equipment', 'image_url', 'is_available'])]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    public const SLOT_START_TIMES = ['09:00', '10:30', '12:00', '13:30', '15:00', '16:30'];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'equipment' => 'array',
            'is_available' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailableBetween(CarbonImmutable $startsAt, CarbonImmutable $endsAt): bool
    {
        if (! $this->is_available || ! $startsAt->isFuture()) {
            return false;
        }

        return ! $this->bookings()
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->exists();
    }
}
