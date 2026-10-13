<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'building' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date', 'after_or_equal:today'],
            'capacity' => ['nullable', 'in:1-2,3-4,5+'],
        ]);

        $selectedDate = $filters['date'] ?? today()->toDateString();
        $roomQuery = Room::query()->where('is_available', true);

        if (! empty($filters['building'])) {
            $roomQuery->where('building', $filters['building']);
        }

        if (($filters['capacity'] ?? null) === '1-2') {
            $roomQuery->whereBetween('capacity', [1, 2]);
        } elseif (($filters['capacity'] ?? null) === '3-4') {
            $roomQuery->whereBetween('capacity', [3, 4]);
        } elseif (($filters['capacity'] ?? null) === '5+') {
            $roomQuery->where('capacity', '>=', 5);
        }

        $rooms = $roomQuery->orderBy('building')->orderBy('name')->get();
        $availableRoomCount = Room::query()->where('is_available', true)->count();
        $dateStart = CarbonImmutable::parse($selectedDate)->startOfDay();
        $dateEnd = $dateStart->endOfDay();
        $activeBookings = Booking::query()
            ->whereIn('room_id', $rooms->modelKeys())
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->where('starts_at', '<', $dateEnd)
            ->where('ends_at', '>', $dateStart)
            ->get(['room_id', 'starts_at', 'ends_at']);

        $nextAvailableSlots = $rooms->mapWithKeys(function (Room $room) use ($activeBookings, $selectedDate): array {
            foreach (Room::SLOT_START_TIMES as $time) {
                $startsAt = CarbonImmutable::parse($selectedDate.' '.$time);
                $endsAt = $startsAt->addMinutes(90);
                $isBooked = $activeBookings->contains(fn (Booking $booking): bool => $booking->room_id === $room->id
                    && $booking->starts_at->lt($endsAt)
                    && $booking->ends_at->gt($startsAt)
                );

                if ($startsAt->isFuture() && ! $isBooked) {
                    return [$room->id => [
                        'range' => $startsAt->format('g:i A').'–'.$endsAt->format('g:i A'),
                    ]];
                }
            }

            return [$room->id => null];
        })->all();

        $availabilitySummary = collect(['09:00', '11:00', '14:00', '17:00'])
            ->map(function (string $time) use ($availableRoomCount, $selectedDate): array {
                $startsAt = CarbonImmutable::parse($selectedDate.' '.$time);
                $endsAt = $startsAt->addMinutes(90);
                $busyRoomCount = Booking::query()
                    ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                    ->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt)
                    ->distinct('room_id')
                    ->count('room_id');
                $freeCount = $startsAt->isFuture() ? max(0, $availableRoomCount - $busyRoomCount) : 0;

                return [
                    'time' => CarbonImmutable::parse($time)->format('H:i'),
                    'free' => $freeCount,
                    'percent' => $availableRoomCount === 0 ? 0 : (int) round($freeCount / $availableRoomCount * 100),
                ];
            });

        return view('index', [
            'rooms' => $rooms,
            'buildings' => Room::query()->distinct()->orderBy('building')->pluck('building'),
            'availableRoomCount' => $availableRoomCount,
            'selectedDate' => $selectedDate,
            'selectedBuilding' => $filters['building'] ?? '',
            'selectedCapacity' => $filters['capacity'] ?? '',
            'nextAvailableSlots' => $nextAvailableSlots,
            'availabilitySummary' => $availabilitySummary,
        ]);
    }
}
