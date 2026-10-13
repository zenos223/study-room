<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'student' => ['nullable', 'string', 'max:255'],
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'status' => ['nullable', Rule::in([Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED])],
            'date' => ['nullable', 'date'],
        ]);
        $bookings = Booking::query()->with(['user', 'room']);

        if (! empty($filters['student'])) {
            $bookings->whereHas('user', fn ($query) => $query
                ->where('name', 'like', '%'.$filters['student'].'%')
                ->orWhere('student_id', 'like', '%'.$filters['student'].'%')
                ->orWhere('email', 'like', '%'.$filters['student'].'%'));
        }

        $bookings->when($filters['room_id'] ?? null, fn ($query, int $roomId) => $query->where('room_id', $roomId));
        $bookings->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status));
        $bookings->when($filters['date'] ?? null, fn ($query, string $date) => $query->whereDate('starts_at', $date));

        return view('admin.bookings', [
            'bookings' => $bookings->orderByDesc('starts_at')->paginate(20)->withQueryString(),
            'rooms' => Room::query()->orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED])],
        ]);

        $booking->update($validated);

        return back()->with('status', 'Booking status updated.');
    }
}
