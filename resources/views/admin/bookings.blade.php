<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings | StudySpace</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root { --color-ink: #17211B; --color-paper: #F7F7F2; --color-sage: #DCE7DD; --color-moss: #53665A; --color-line: #E4E8E2; }</style>
</head>
<body class="bg-paper text-ink antialiased">
<div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
    <aside class="border-b border-line bg-white p-5 lg:border-b-0 lg:border-r">
        <a href="{{ route('admin.rooms.index') }}" class="font-semibold">StudySpace Admin</a>
        <nav class="mt-8 space-y-1 text-sm">
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Rooms</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 rounded-xl bg-ink px-3 py-2.5 font-medium text-white">Bookings</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Users</a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">View site</a>
        </nav>
    </aside>
    <main class="p-5 lg:p-8">
        <div><p class="text-sm text-ink/50">Reservations</p><h1 class="mt-1 text-3xl font-semibold">Bookings</h1></div>
        @if (session('status'))<p class="mt-5 border border-sage bg-white p-3 text-sm">{{ session('status') }}</p>@endif
        <div class="mt-8 overflow-hidden rounded-2xl border border-line bg-white">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid gap-3 border-b border-line p-4 sm:grid-cols-4">
                <input name="student" value="{{ $filters['student'] ?? '' }}" placeholder="Search student..." class="rounded-xl border border-line px-4 py-2.5 text-sm">
                <select name="room_id" class="rounded-xl border border-line bg-white px-4 py-2.5 text-sm"><option value="">All rooms</option>@foreach ($rooms as $room)<option value="{{ $room->id }}" @selected((string) ($filters['room_id'] ?? '') === (string) $room->id)>{{ $room->name }}</option>@endforeach</select>
                <select name="status" class="rounded-xl border border-line bg-white px-4 py-2.5 text-sm"><option value="">All status</option>@foreach (['pending', 'confirmed', 'cancelled'] as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
                <div class="flex gap-2"><input name="date" type="date" value="{{ $filters['date'] ?? '' }}" class="min-w-0 flex-1 rounded-xl border border-line px-4 py-2.5 text-sm"><button class="rounded-xl border border-line px-3 text-sm">Filter</button></div>
            </form>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-paper text-xs uppercase tracking-wide text-ink/50"><tr><th class="px-5 py-4">Student</th><th class="px-5 py-4">Room</th><th class="px-5 py-4">Date &amp; time</th><th class="px-5 py-4">Purpose</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Action</th></tr></thead>
                    <tbody class="divide-y divide-line">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-5 py-4"><p class="font-semibold">{{ $booking->user->name }}</p><p class="mt-1 text-xs text-ink/50">{{ $booking->student_id }} · {{ $booking->user->email }}</p></td>
                            <td class="px-5 py-4">{{ $booking->room->name }}</td>
                            <td class="px-5 py-4"><p>{{ $booking->starts_at->format('M j, Y') }}</p><p class="mt-1 text-xs text-ink/50">{{ $booking->starts_at->format('g:i A') }}–{{ $booking->ends_at->format('g:i A') }}</p></td>
                            <td class="px-5 py-4">{{ $booking->purpose }}</td>
                            <td class="px-5 py-4">{{ ucfirst($booking->status) }}</td>
                            <td class="px-5 py-4"><form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="flex items-center gap-2">@csrf @method('PATCH')<select name="status" class="rounded-lg border border-line bg-white px-2 py-2 text-xs">@foreach (['pending', 'confirmed', 'cancelled'] as $status)<option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select><button class="font-medium underline">Save</button></form></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-ink/50">No bookings match these filters.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $bookings->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>
