<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users | StudySpace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{ink:'#17211B',paper:'#F7F7F2',sage:'#DCE7DD',moss:'#53665A',line:'#E4E8E2'}}}}</script>
</head>
<body class="bg-paper text-ink antialiased">
<div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
    <aside class="border-b border-line bg-white p-5 lg:border-b-0 lg:border-r">
        <a href="{{ route('admin.rooms.index') }}" class="font-semibold">StudySpace Admin</a>
        <nav class="mt-8 space-y-1 text-sm">
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Rooms</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Bookings</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl bg-ink px-3 py-2.5 font-medium text-white">Users</a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">View site</a>
        </nav>
    </aside>
    <main class="p-5 lg:p-8">
        <div><p class="text-sm text-ink/50">Accounts and access</p><h1 class="mt-1 text-3xl font-semibold">Users</h1></div>
        @if (session('status'))<p class="mt-5 border border-sage bg-white p-3 text-sm">{{ session('status') }}</p>@endif
        @error('role')<p class="mt-5 border border-red-200 bg-white p-3 text-sm text-red-700">{{ $message }}</p>@enderror
        <div class="mt-8 overflow-hidden rounded-2xl border border-line bg-white">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3 border-b border-line p-4">
                <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name, email, or student ID..." class="w-full rounded-xl border border-line px-4 py-2.5 text-sm sm:max-w-md">
                <button class="rounded-xl border border-line px-4 py-2.5 text-sm">Search</button>
            </form>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-paper text-xs uppercase tracking-wide text-ink/50"><tr><th class="px-5 py-4">User</th><th class="px-5 py-4">Student ID</th><th class="px-5 py-4">Department</th><th class="px-5 py-4">Role</th><th class="px-5 py-4">Access</th></tr></thead>
                    <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-5 py-4"><p class="font-semibold">{{ $user->name }}</p><p class="mt-1 text-xs text-ink/50">{{ $user->email }}</p></td>
                            <td class="px-5 py-4">{{ $user->student_id ?: '—' }}</td>
                            <td class="px-5 py-4">{{ $user->department ?: '—' }}</td>
                            <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->role === \App\Models\User::ROLE_ADMIN ? 'bg-sage text-ink' : 'bg-paper text-ink/60' }}">{{ ucfirst($user->role) }}</span></td>
                            <td class="px-5 py-4">
                                @if ($user->is(auth()->user()))
                                    <span class="text-xs text-ink/50">Current account</span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.role.update', $user) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="rounded-lg border border-line bg-white px-2 py-2 text-xs">
                                            <option value="user" @selected($user->role === \App\Models\User::ROLE_USER)>User</option>
                                            <option value="admin" @selected($user->role === \App\Models\User::ROLE_ADMIN)>Admin</option>
                                        </select>
                                        <button class="font-medium underline">Save</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-ink/50">No users match this search.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $users->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>
