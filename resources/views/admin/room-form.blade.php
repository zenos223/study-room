<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->exists ? 'Edit' : 'Add' }} Room | StudySpace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{ink:'#17211B',paper:'#F7F7F2',sage:'#DCE7DD',moss:'#53665A',line:'#E4E8E2'}}}}</script>
</head>
<body class="bg-paper text-ink antialiased">
<div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
    <aside class="border-b border-line bg-white p-5 lg:border-b-0 lg:border-r">
        <a href="{{ route('admin.rooms.index') }}" class="font-semibold">StudySpace Admin</a>
        <nav class="mt-8 space-y-1 text-sm"><a href="{{ route('admin.rooms.index') }}" class="block rounded-xl bg-ink px-3 py-2.5 font-medium text-white">Rooms</a><a href="{{ route('admin.bookings.index') }}" class="block rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">Bookings</a><a href="{{ route('home') }}" class="block rounded-xl px-3 py-2.5 text-ink/60 hover:bg-paper">View site</a></nav>
    </aside>
    <main class="p-5 lg:p-8">
        <p class="text-sm text-ink/50">Manage spaces</p><h1 class="mt-1 text-3xl font-semibold">{{ $room->exists ? 'Edit room' : 'Add room' }}</h1>
        <form method="POST" action="{{ $room->exists ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}" class="mt-8 max-w-3xl space-y-5 border border-line bg-white p-6">
            @csrf
            @if ($room->exists) @method('PUT') @endif
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="block"><span class="text-sm font-medium">Room name</span><input name="name" value="{{ old('name', $room->name) }}" required class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm">@error('name')<span class="text-sm text-red-700">{{ $message }}</span>@enderror</label>
                <label class="block"><span class="text-sm font-medium">Building</span><input name="building" value="{{ old('building', $room->building) }}" required class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm">@error('building')<span class="text-sm text-red-700">{{ $message }}</span>@enderror</label>
                <label class="block"><span class="text-sm font-medium">Floor</span><input name="floor" value="{{ old('floor', $room->floor) }}" placeholder="Level 2" class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm"></label>
                <label class="block"><span class="text-sm font-medium">Location</span><input name="location" value="{{ old('location', $room->location) }}" placeholder="East Wing" class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm"></label>
                <label class="block"><span class="text-sm font-medium">Capacity</span><input name="capacity" type="number" min="1" max="100" value="{{ old('capacity', $room->capacity) }}" required class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm"></label>
                <label class="block"><span class="text-sm font-medium">Category</span><select name="category" required class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm">@foreach ($categories as $category)<option value="{{ $category }}" @selected(old('category', $room->category) === $category)>{{ $category }}</option>@endforeach</select></label>
            </div>
            <label class="block"><span class="text-sm font-medium">Description</span><textarea name="description" rows="3" class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm">{{ old('description', $room->description) }}</textarea></label>
            <fieldset><legend class="text-sm font-medium">Equipment</legend><div class="mt-3 flex flex-wrap gap-4">@foreach (['Whiteboard', 'Power outlets', 'Natural light', 'Display', 'USB-C', 'Monitor', 'Silent zone'] as $equipment)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="equipment[]" value="{{ $equipment }}" @checked(in_array($equipment, old('equipment', $room->equipment ?? []), true))>{{ $equipment }}</label>@endforeach</div></fieldset>
            <label class="block"><span class="text-sm font-medium">Image URL</span><input name="image_url" type="url" value="{{ old('image_url', $room->image_url) }}" class="mt-2 w-full rounded-xl border border-line px-4 py-3 text-sm"></label>
            <input type="hidden" name="is_available" value="0"><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_available" value="1" @checked((bool) old('is_available', $room->is_available ?? true))> Available for booking</label>
            <div class="flex items-center gap-3"><button class="rounded-xl bg-ink px-5 py-3 text-sm font-semibold text-white">{{ $room->exists ? 'Save changes' : 'Add room' }}</button><a href="{{ route('admin.rooms.index') }}" class="text-sm underline">Cancel</a></div>
        </form>
    </main>
</div>
</body>
</html>
