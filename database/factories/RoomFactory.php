<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->firstName().' Room',
            'building' => fake()->randomElement(['Main Library', 'Learning Commons', 'Science Library']),
            'location' => fake()->randomElement(['East Wing', 'North Wing', 'South Wing']),
            'floor' => 'Level '.fake()->numberBetween(1, 4),
            'capacity' => fake()->randomElement([2, 4, 6, 8]),
            'category' => fake()->randomElement(['Quiet study', 'Group study', 'Solo focus']),
            'description' => fake()->sentence(),
            'equipment' => ['Whiteboard', 'Power outlets'],
            'image_url' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1400&q=85',
            'is_available' => true,
        ];
    }
}
