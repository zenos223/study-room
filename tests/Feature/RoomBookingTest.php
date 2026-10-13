<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_route_renders_the_studyspace_room_directory(): void
    {
        Room::factory()->create([
            'name' => 'Oak Room',
            'building' => 'Main Library',
            'capacity' => 4,
        ]);
        Room::factory()->create([
            'name' => 'Maple Room',
            'building' => 'Learning Commons',
            'capacity' => 6,
        ]);

        $this->get(route('home', [
            'building' => 'Main Library',
            'capacity' => '3-4',
            'date' => today()->addDay()->toDateString(),
        ]))
            ->assertOk()
            ->assertSee('Available study rooms')
            ->assertSee('Oak Room')
            ->assertDontSee('Maple Room')
            ->assertDontSee("Let's get started", false);
    }

    public function test_room_detail_displays_database_room_and_available_slots(): void
    {
        $room = Room::factory()->create(['name' => 'Oak Room']);
        $date = today()->addDay()->toDateString();

        $this->get(route('rooms.show', $room).'?date='.$date)
            ->assertOk()
            ->assertSee('Oak Room')
            ->assertSee('9:00 AM')
            ->assertSee('Continue to booking');
    }

    public function test_signed_in_student_can_create_and_cancel_a_booking(): void
    {
        $user = User::factory()->create(['student_id' => 'ST-BOOK-001']);
        $room = Room::factory()->create(['name' => 'Oak Room']);
        $date = today()->addDays(2)->toDateString();

        $this->actingAs($user)->post(route('bookings.store', $room), [
            'date' => $date,
            'time' => '10:30',
            'phone' => '09123456789',
            'purpose' => 'Group study',
            'note' => 'Bring the project notes.',
            'agreement' => '1',
        ])->assertRedirect(route('bookings.index', ['filter' => 'upcoming']));

        $booking = Booking::query()->firstOrFail();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $user->id,
            'room_id' => $room->id,
            'student_id' => 'ST-BOOK-001',
            'status' => Booking::STATUS_PENDING,
        ]);

        $this->actingAs($user)->post(route('bookings.cancel', $booking));

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_CANCELLED,
        ]);
    }

    public function test_an_active_booking_blocks_the_same_room_and_time(): void
    {
        $user = User::factory()->create(['student_id' => 'ST-BOOK-002']);
        $room = Room::factory()->create();
        $date = today()->addDays(2)->toDateString();
        $startsAt = CarbonImmutable::parse($date.' 10:30');

        Booking::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'student_id' => $user->student_id,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->addMinutes(90),
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        $this->actingAs($user)
            ->from(route('rooms.show', $room).'?date='.$date)
            ->post(route('bookings.store', $room), [
                'date' => $date,
                'time' => '10:30',
                'phone' => '09123456789',
                'purpose' => 'Group study',
                'agreement' => '1',
            ])
            ->assertSessionHasErrors('time');

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_student_cannot_cancel_another_students_booking(): void
    {
        $owner = User::factory()->create(['student_id' => 'ST-BOOK-003']);
        $otherStudent = User::factory()->create(['student_id' => 'ST-BOOK-004']);
        $room = Room::factory()->create();
        $startsAt = CarbonImmutable::parse(today()->addDays(2)->toDateString().' 10:30');
        $booking = Booking::factory()->create([
            'user_id' => $owner->id,
            'room_id' => $room->id,
            'student_id' => $owner->student_id,
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->addMinutes(90),
        ]);

        $this->actingAs($otherStudent)
            ->post(route('bookings.cancel', $booking))
            ->assertNotFound();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }

    public function test_admin_can_create_a_room(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->actingAs($admin)->post(route('admin.rooms.store'), [
            'name' => 'Cedar Room',
            'building' => 'Main Library',
            'location' => 'South Wing',
            'floor' => 'Level 1',
            'capacity' => 8,
            'category' => 'Group study',
            'description' => 'A room for collaborative work.',
            'equipment' => ['Display', 'Whiteboard'],
            'is_available' => true,
        ])->assertRedirect(route('admin.rooms.index'));

        $this->assertDatabaseHas('rooms', [
            'name' => 'Cedar Room',
            'capacity' => 8,
            'is_available' => true,
        ]);
    }

    public function test_admin_can_review_and_update_a_booking(): void
    {
        $student = User::factory()->create(['student_id' => 'ST-BOOK-005']);
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();
        $room = Room::factory()->create(['name' => 'Study Room']);
        $booking = Booking::factory()->create([
            'user_id' => $student->id,
            'room_id' => $room->id,
            'student_id' => $student->student_id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee('Study Room')
            ->assertSee($student->name);

        $this->patch(route('admin.bookings.update', $booking), [
            'status' => Booking::STATUS_CONFIRMED,
        ])->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }
}
