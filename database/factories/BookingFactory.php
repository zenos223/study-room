<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = now()->addDay()->setTime(10, 30);

        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'student_id' => fake()->unique()->numerify('ST-2026-####'),
            'phone' => '09000000000',
            'purpose' => 'Individual study',
            'note' => null,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addMinutes(90),
            'status' => Booking::STATUS_CONFIRMED,
        ];
    }
}
