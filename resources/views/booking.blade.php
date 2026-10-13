<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Complete Booking — StudySpace</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={theme:{extend:{colors:{navy:'#0F172A',blue:'#2563EB',mist:'#F8FAFC',line:'#E2E8F0',muted:'#64748B'}}}}</script>
</head>
<body class="bg-mist text-navy antialiased">
<header class="border-b border-line bg-white"><div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4"><a href="{{ route('home') }}" class="flex items-center gap-3 font-semibold"><span class="grid h-9 w-9 place-items-center rounded-full bg-navy text-white"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 4.5h10.5A2.5 2.5 0 0 1 18 7v12H7.5A2.5 2.5 0 0 0 5 21.5z"/><path d="M5 4.5v17A2.5 2.5 0 0 1 7.5 19H18"/></svg></span>StudySpace</a><a href="{{ route('bookings.index') }}" class="text-sm font-medium text-muted">My bookings</a></div></header>
<main class="mx-auto max-w-6xl px-5 py-10 lg:py-14">
  <div class="mb-8"><p class="text-sm font-semibold text-blue">Final step</p><h1 class="mt-2 text-4xl font-semibold tracking-[-0.035em]">Complete your reservation</h1><p class="mt-3 text-muted">Add your details and confirm the selected room and time.</p></div>
  <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
    <form id="bookingForm" method="POST" action="{{ route('bookings.store', $room) }}" class="rounded-3xl border border-line bg-white p-6 sm:p-8">
      @csrf
      <input type="hidden" name="date" value="{{ $selectedDate }}">
      <input type="hidden" name="time" value="{{ $selectedTime }}">
      @error('time')<p class="mb-4 text-sm text-red-700">{{ $message }}</p>@enderror
      <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="text-sm font-semibold">Full name</label><input type="text" value="{{ auth()->user()->name }}" readonly class="mt-2 w-full rounded-2xl border border-line px-4 py-3 text-sm outline-none focus:border-blue focus:ring-4 focus:ring-blue/10"></div>
        <div><label class="text-sm font-semibold">Student ID</label><input type="text" value="{{ auth()->user()->student_id }}" readonly class="mt-2 w-full rounded-2xl border border-line px-4 py-3 text-sm outline-none focus:border-blue focus:ring-4 focus:ring-blue/10"></div>
        <div><label class="text-sm font-semibold">Email</label><input type="email" value="{{ auth()->user()->email }}" readonly class="mt-2 w-full rounded-2xl border border-line px-4 py-3 text-sm outline-none focus:border-blue focus:ring-4 focus:ring-blue/10"></div>
        <div><label class="text-sm font-semibold" for="phone">Phone</label><input id="phone" name="phone" type="text" value="{{ old('phone') }}" placeholder="09xxxxxxxxx" required class="mt-2 w-full rounded-2xl border border-line px-4 py-3 text-sm outline-none focus:border-blue focus:ring-4 focus:ring-blue/10">@error('phone')<span class="text-sm text-red-700">{{ $message }}</span>@enderror</div>
      </div>
      <div class="mt-5"><label class="text-sm font-semibold" for="purpose">Purpose</label><select id="purpose" name="purpose" required class="mt-2 w-full rounded-2xl border border-line bg-white px-4 py-3 text-sm outline-none focus:border-blue"><option @selected(old('purpose', 'Individual study') === 'Individual study')>Individual study</option><option @selected(old('purpose') === 'Group study')>Group study</option><option @selected(old('purpose') === 'Project meeting')>Project meeting</option><option @selected(old('purpose') === 'Presentation practice')>Presentation practice</option></select>@error('purpose')<span class="text-sm text-red-700">{{ $message }}</span>@enderror</div>
      <div class="mt-5"><label class="text-sm font-semibold" for="note">Note <span class="font-normal text-muted">(optional)</span></label><textarea id="note" name="note" rows="4" placeholder="Anything we should know?" class="mt-2 w-full rounded-2xl border border-line px-4 py-3 text-sm outline-none focus:border-blue focus:ring-4 focus:ring-blue/10">{{ old('note') }}</textarea>@error('note')<span class="text-sm text-red-700">{{ $message }}</span>@enderror</div>
      <label class="mt-6 flex items-start gap-3 text-sm leading-6 text-muted"><input name="agreement" value="1" type="checkbox" required class="mt-1 h-4 w-4 rounded border-line accent-blue"><span>I agree to follow the room guidelines and leave at the end of my reserved time.</span></label>
      @error('agreement')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
    </form>

    <aside class="h-fit rounded-3xl border border-line bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wider text-muted">Reservation summary</p><div class="mt-5 flex gap-4"><img src="{{ $room->image_url ?: 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=300&q=80' }}" class="h-20 w-24 rounded-2xl object-cover" alt="{{ $room->name }}"><div><p class="font-semibold">{{ $room->name }}</p><p class="mt-1 text-sm text-muted">{{ $room->building }} · {{ $room->floor }}</p><p class="mt-2 text-xs font-medium text-blue">{{ $room->capacity }} seats · {{ implode(', ', $room->equipment ?? []) }}</p></div></div><div class="mt-6 space-y-4 border-t border-line pt-5 text-sm"><div class="flex justify-between gap-4"><span class="text-muted">Date</span><span class="font-semibold text-right">{{ $startsAt->format('M j, Y') }}</span></div><div class="flex justify-between gap-4"><span class="text-muted">Time</span><span class="font-semibold text-right">{{ $startsAt->format('g:i A') }}–{{ $endsAt->format('g:i A') }}</span></div><div class="flex justify-between"><span class="text-muted">Duration</span><span class="font-semibold">1 hr 30 min</span></div></div><button form="bookingForm" type="submit" class="mt-6 block w-full rounded-2xl bg-blue px-4 py-3.5 text-center text-sm font-semibold text-white hover:bg-blue-700">Confirm booking</button><a href="{{ route('rooms.show', $room) }}?date={{ $selectedDate }}" class="mt-3 block text-center text-sm font-medium text-muted hover:text-navy">Change room or time</a></aside>
  </div>
</main>
</body>
</html>
