<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Rooms | StudySpace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{ink:'#17211B',paper:'#F7F7F2',sage:'#DCE7DD',moss:'#53665A',line:'#E4E8E2'}}}}</script>
</head>
<body class="bg-paper text-ink antialiased">
<div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
    <aside class="border-b border-line bg-white p-5 lg:border-b-0 lg:border-r">
        <a href="{{ route('admin.rooms.index') }}" class="font-semibold">StudySpace Admin</a>
        <nav class="mt-8 space-y-1 text-sm">
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 rounded-xl bg-ink px-3 py-2.5 font-medium text-white">Rooms</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Bookings</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Users</a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">View site</a>
        </nav>
    </aside>
    <main class="p-5 lg:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-sm text-ink/50">Manage spaces</p><h1 class="mt-1 text-3xl font-semibold">Rooms</h1></div>
            <a href="{{ route('admin.rooms.create') }}" class="rounded-xl bg-ink px-4 py-3 text-sm font-medium text-white">+ Add room</a>
        </div>
        @if (session('status'))<p class="mt-5 border border-sage bg-white p-3 text-sm">{{ session('status') }}</p>@endif
        @error('room')<p class="mt-5 border border-red-200 bg-white p-3 text-sm text-red-700">{{ $message }}</p>@enderror
        <div class="mt-8 overflow-hidden rounded-2xl border border-line bg-white">
            <form method="GET" action="{{ route('admin.rooms.index') }}" class="flex flex-col gap-3 border-b border-line p-4 sm:flex-row">
                <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search rooms..." class="w-full rounded-xl border border-line px-4 py-2.5 text-sm sm:max-w-xs">
                <select name="status" class="rounded-xl border border-line bg-white px-4 py-2.5 text-sm">
                    <option value="">All status</option>
                    <option value="available" @selected(($filters['status'] ?? '') === 'available')>Available</option>
                    <option value="unavailable" @selected(($filters['status'] ?? '') === 'unavailable')>Unavailable</option>
                </select>
                <button class="rounded-xl border border-line px-4 py-2.5 text-sm">Filter</button>
            </form>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-paper text-xs uppercase tracking-wide text-ink/50"><tr><th class="px-5 py-4">Room</th><th class="px-5 py-4">Location</th><th class="px-5 py-4">Capacity</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Actions</th></tr></thead>
                    <tbody class="divide-y divide-line">
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="px-5 py-4"><p class="font-semibold">{{ $room->name }}</p><p class="mt-1 text-xs text-ink/50">{{ $room->category }}</p></td>
                            <td class="px-5 py-4">{{ $room->floor }} · {{ $room->location ?: $room->building }}<p class="mt-1 text-xs text-ink/50">{{ $room->building }}</p></td>
                            <td class="px-5 py-4">{{ $room->capacity }}</td>
                            <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $room->is_available ? 'bg-sage text-ink' : 'bg-paper text-ink/50' }}">{{ $room->is_available ? 'Available' : 'Unavailable' }}</span></td>
                            <td class="px-5 py-4"><div class="flex items-center gap-3"><a href="{{ route('admin.rooms.edit', $room) }}" class="font-medium underline">Edit</a><form method="POST" action="{{ route('admin.rooms.destroy', $room) }}">@csrf @method('DELETE')<button class="font-medium text-red-700 underline">Delete</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-ink/50">No rooms have been added yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $rooms->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>
