<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    private const PURPOSES = [
        'Individual study',
        'Group study',
        'Project meeting',
        'Presentation practice',
    ];

    public function create(Request $request, Room $room): View|RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', Rule::in(Room::SLOT_START_TIMES)],
        ]);
        $startsAt = CarbonImmutable::parse($validated['date'].' '.$validated['time']);
        $endsAt = $startsAt->addMinutes(90);

        if (! $room->isAvailableBetween($startsAt, $endsAt)) {
            return redirect()->route('rooms.show', [$room, 'date' => $validated['date']])
                ->withErrors(['slot' => 'That time is no longer available. Choose another slot.']);
        }

        return view('booking', [
            'room' => $room,
            'selectedDate' => $validated['date'],
            'selectedTime' => $validated['time'],
            'startsAt' => $startsAt,
            'endsAt' => $endsAt,
        ]);
    }

    public function store(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', Rule::in(Room::SLOT_START_TIMES)],
            'phone' => ['required', 'string', 'max:40'],
            'purpose' => ['required', Rule::in(self::PURPOSES)],
            'note' => ['nullable', 'string', 'max:2000'],
            'agreement' => ['accepted'],
        ]);

        $startsAt = CarbonImmutable::parse($validated['date'].' '.$validated['time']);
        $endsAt = $startsAt->addMinutes(90);
        $user = $request->user();

        $booking = DB::transaction(function () use ($room, $startsAt, $endsAt, $validated, $user): Booking {
            $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

            if (! $lockedRoom->isAvailableBetween($startsAt, $endsAt)) {
                throw ValidationException::withMessages([
                    'time' => 'That time is no longer available. Choose another slot.',
                ]);
            }

            return Booking::query()->create([
                'user_id' => $user->id,
                'room_id' => $lockedRoom->id,
                'student_id' => $user->student_id,
                'phone' => $validated['phone'],
                'purpose' => $validated['purpose'],
                'note' => $validated['note'] ?? null,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => Booking::STATUS_PENDING,
            ]);
        });

        return redirect()->route('bookings.index', ['filter' => 'upcoming'])
            ->with('status', 'Your room request has been submitted.');
    }

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'filter' => ['nullable', Rule::in(['upcoming', 'past', 'cancelled'])],
        ]);
        $filter = $validated['filter'] ?? 'upcoming';
        $bookings = $request->user()->bookings()->with('room');

        if ($filter === 'cancelled') {
            $bookings->where('status', Booking::STATUS_CANCELLED);
        } elseif ($filter === 'past') {
            $bookings->where('status', '!=', Booking::STATUS_CANCELLED)
                ->where('ends_at', '<', now());
        } else {
            $bookings->where('status', '!=', Booking::STATUS_CANCELLED)
                ->where('ends_at', '>=', now());
        }

        return view('my-bookings', [
            'bookings' => $bookings->orderBy('starts_at')->paginate(10)->withQueryString(),
            'filter' => $filter,
        ]);
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $ownedBooking = $request->user()->bookings()->findOrFail($booking->id);

        if (! $ownedBooking->starts_at->isFuture() || $ownedBooking->status === Booking::STATUS_CANCELLED) {
            return back()->withErrors(['booking' => 'This booking can no longer be cancelled.']);
        }

        $ownedBooking->update(['status' => Booking::STATUS_CANCELLED]);

        return back()->with('status', 'Your booking was cancelled.');
    }
}
