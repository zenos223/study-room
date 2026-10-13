<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
{
    private const CATEGORIES = ['Quiet study', 'Group study', 'Solo focus'];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['available', 'unavailable'])],
        ]);
        $rooms = Room::query()
            ->when($filters['search'] ?? null, fn ($query, string $search) => $query
                ->where(fn ($query) => $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('building', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')))
            ->when(isset($filters['status']), fn ($query) => $query->where('is_available', $filters['status'] === 'available'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.rooms', ['rooms' => $rooms, 'filters' => $filters]);
    }

    public function create(): View
    {
        return view('admin.room-form', ['room' => new Room, 'categories' => self::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        Room::query()->create($this->validatedRoom($request));

        return redirect()->route('admin.rooms.index')->with('status', 'Room added.');
    }

    public function edit(Room $room): View
    {
        return view('admin.room-form', ['room' => $room, 'categories' => self::CATEGORIES]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $room->update($this->validatedRoom($request));

        return redirect()->route('admin.rooms.index')->with('status', 'Room updated.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->bookings()->exists()) {
            return back()->withErrors(['room' => 'This room has booking history. Mark it unavailable instead of deleting it.']);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('status', 'Room deleted.');
    }

    /** @return array<string, mixed> */
    private function validatedRoom(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'building' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'category' => ['required', Rule::in(self::CATEGORIES)],
            'description' => ['nullable', 'string', 'max:5000'],
            'equipment' => ['nullable', 'array', 'max:12'],
            'equipment.*' => ['required', 'string', 'max:100'],
            'image_url' => ['nullable', 'url', 'max:2000'],
            'is_available' => ['required', 'boolean'],
        ]);
    }
}
