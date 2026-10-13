<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function show(Request $request, Room $room): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);
        $selectedDate = $validated['date'] ?? today()->toDateString();
        $slots = collect(Room::SLOT_START_TIMES)->map(function (string $time) use ($room, $selectedDate): array {
            $startsAt = CarbonImmutable::parse($selectedDate.' '.$time);
            $endsAt = $startsAt->addMinutes(90);

            return [
                'time' => $time,
                'label' => $startsAt->format('g:i A'),
                'range' => $startsAt->format('g:i A').'–'.$endsAt->format('g:i A'),
                'available' => $room->isAvailableBetween($startsAt, $endsAt),
            ];
        });

        return view('room-detail', [
            'room' => $room,
            'selectedDate' => $selectedDate,
            'slots' => $slots,
        ]);
    }
}
