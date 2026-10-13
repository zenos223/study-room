<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StudySpace — Room Booking</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config={theme:{extend:{colors:{paper:'#F6F3EC',ink:'#202724',pine:'#173A34',sage:'#DCE7E1',line:'#D9DDD8',muted:'#66716C',sand:'#E9E1D2'}}}}
  </script>
</head>
<body class="bg-paper text-ink antialiased">
  <header class="border-b border-line bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 lg:px-8">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <span class="grid h-9 w-9 place-items-center border border-pine bg-pine text-white">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5.5h11a3 3 0 0 1 3 3V20H7a3 3 0 0 0-3 3z"/><path d="M4 5.5V23"/></svg>
        </span>
        <div>
          <p class="text-sm font-semibold tracking-tight">StudySpace</p>
          <p class="text-[10px] uppercase tracking-[0.18em] text-muted">Library Room Booking</p>
        </div>
      </a>

      <nav class="hidden items-center gap-7 text-sm md:flex">
        <a href="#rooms" class="font-medium text-pine">Rooms</a>
        <a href="{{ route('bookings.index') }}" class="text-muted hover:text-ink">My bookings</a>
        <a href="#how" class="text-muted hover:text-ink">How it works</a>
      </nav>

      <div class="flex items-center gap-2">
        @guest
          <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-muted hover:text-ink">Log in</a>
          <a href="{{ route('register') }}" class="border border-pine bg-pine px-4 py-2 text-sm font-semibold text-white hover:bg-[#102e29]">Create account</a>
        @else
          <span class="px-3 py-2 text-sm text-muted">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}">@csrf<button class="border border-pine bg-pine px-4 py-2 text-sm font-semibold text-white">Log out</button></form>
        @endguest
      </div>
    </div>
  </header>

  <main>
    <section class="border-b border-line bg-white">
      <div class="mx-auto grid max-w-6xl gap-10 px-5 py-12 lg:grid-cols-[1fr_.82fr] lg:px-8 lg:py-16">
        <div class="flex flex-col justify-center">
          <div class="mb-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-pine">
            <span class="h-px w-10 bg-pine"></span>
            University Library
          </div>
          <h1 class="max-w-2xl text-4xl font-semibold leading-[1.08] tracking-[-0.035em] sm:text-5xl">Book a study room without the back-and-forth.</h1>
          <p class="mt-5 max-w-xl text-base leading-7 text-muted">Choose a room, check the available time, and reserve your study session in a few steps.</p>

          <div class="mt-8 flex flex-wrap gap-3">
            <a href="#rooms" class="bg-pine px-5 py-3 text-sm font-semibold text-white hover:bg-[#102e29]">Browse rooms</a>
            <a href="{{ route('bookings.index') }}" class="border border-line bg-paper px-5 py-3 text-sm font-semibold hover:border-ink">View my bookings</a>
          </div>
        </div>

        <div class="border border-line bg-paper p-5 sm:p-6">
          <div class="flex items-center justify-between border-b border-line pb-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">{{ \Carbon\Carbon::parse($selectedDate)->isToday() ? 'Today' : \Carbon\Carbon::parse($selectedDate)->format('D, M j') }}</p>
              <h2 class="mt-1 text-xl font-semibold">Library availability</h2>
            </div>
              <span class="border border-pine/20 bg-sage px-2.5 py-1 text-xs font-semibold text-pine">Open until 9 PM</span>
          </div>

          <div class="mt-5 space-y-4">
            @foreach ($availabilitySummary as $slot)
              <div class="grid grid-cols-[72px_1fr_auto] items-center gap-3">
                <span class="text-xs font-semibold text-muted">{{ $slot['time'] }}</span>
                <div class="h-2 bg-white"><div class="h-2 bg-pine" style="width: {{ $slot['percent'] }}%"></div></div>
                <span class="text-xs font-medium">{{ $slot['free'] }} free</span>
              </div>
            @endforeach
          </div>

          <div class="mt-6 grid grid-cols-3 border-t border-line pt-5 text-center">
            <div><p class="text-xl font-semibold">{{ $availableRoomCount }}</p><p class="mt-1 text-[10px] uppercase tracking-wider text-muted">Rooms</p></div>
            <div class="border-x border-line"><p class="text-xl font-semibold">8 AM</p><p class="mt-1 text-[10px] uppercase tracking-wider text-muted">Opens</p></div>
            <div><p class="text-xl font-semibold">9 PM</p><p class="mt-1 text-[10px] uppercase tracking-wider text-muted">Closes</p></div>
          </div>
        </div>
      </div>
    </section>

    <section class="border-b border-line bg-paper">
      <div class="mx-auto max-w-6xl px-5 py-6 lg:px-8">
        <form method="GET" action="{{ route('home') }}" class="grid gap-3 md:grid-cols-[1fr_1fr_.7fr_auto]">
          <label class="border border-line bg-white px-4 py-3">
            <span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-muted">Building</span>
            <select name="building" class="mt-1 w-full bg-transparent text-sm font-semibold outline-none"><option value="">All buildings</option>@foreach ($buildings as $building)<option value="{{ $building }}" @selected($selectedBuilding === $building)>{{ $building }}</option>@endforeach</select>
          </label>
          <label class="border border-line bg-white px-4 py-3">
            <span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-muted">Date</span>
            <input name="date" type="date" value="{{ $selectedDate }}" min="{{ today()->toDateString() }}" class="mt-1 w-full bg-transparent text-sm font-semibold outline-none">
          </label>
          <label class="border border-line bg-white px-4 py-3">
            <span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-muted">Capacity</span>
            <select name="capacity" class="mt-1 w-full bg-transparent text-sm font-semibold outline-none"><option value="" @selected($selectedCapacity === '')>Any</option><option value="1-2" @selected($selectedCapacity === '1-2')>1–2</option><option value="3-4" @selected($selectedCapacity === '3-4')>3–4</option><option value="5+" @selected($selectedCapacity === '5+')>5+</option></select>
          </label>
          <button type="submit" class="flex items-center justify-center bg-ink px-6 py-3 text-sm font-semibold text-white hover:bg-pine">Check rooms</button>
        </form>
      </div>
    </section>

    <section id="rooms" class="mx-auto max-w-6xl px-5 py-14 lg:px-8">
      <div class="flex flex-col gap-3 border-b border-line pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.16em] text-pine">Room directory</p>
          <h2 class="mt-2 text-3xl font-semibold tracking-[-0.025em]">Available study rooms</h2>
        </div>
        <p class="text-sm text-muted">{{ \Carbon\Carbon::parse($selectedDate)->format('l, d F') }}</p>
      </div>

      <div class="divide-y divide-line border-b border-line">
        @forelse ($rooms as $room)
        <article class="grid gap-5 py-6 md:grid-cols-[1.3fr_.7fr_auto] md:items-center">
          <div>
            <div class="flex flex-wrap items-center gap-3"><h3 class="text-lg font-semibold">{{ $room->name }}</h3><span class="bg-sage px-2 py-1 text-[11px] font-semibold text-pine">{{ $room->category }}</span></div>
            <p class="mt-2 text-sm text-muted">{{ $room->building }} · {{ $room->floor }} · {{ $room->capacity }} seats</p>
            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-muted">@foreach ($room->equipment ?? [] as $equipment)<span>{{ $equipment }}</span>@endforeach</div>
          </div>
          <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-muted">Next available</p>
            <p class="mt-1 text-base font-semibold">{{ $nextAvailableSlots[$room->id]['range'] ?? 'No slots available' }}</p>
          </div>
          <a href="{{ route('rooms.show', $room) }}?date={{ $selectedDate }}" class="border border-ink px-4 py-2.5 text-center text-sm font-semibold hover:bg-ink hover:text-white">View schedule</a>
        </article>
        @empty
          <p class="py-8 text-sm text-muted">No available rooms match those filters. Try another building or capacity.</p>
        @endforelse
      </div>
    </section>

    <section id="how" class="bg-pine text-white">
      <div class="mx-auto max-w-6xl px-5 py-12 lg:px-8">
        <div class="grid gap-8 md:grid-cols-[.7fr_1.3fr]">
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/60">Simple process</p>
            <h2 class="mt-2 text-2xl font-semibold">Three steps to a quiet desk.</h2>
          </div>
          <div class="grid gap-6 sm:grid-cols-3">
            <div class="border-t border-white/25 pt-4"><p class="text-xs text-white/50">01</p><p class="mt-2 font-semibold">Choose a room</p><p class="mt-2 text-sm leading-6 text-white/65">Check location, capacity, and facilities.</p></div>
            <div class="border-t border-white/25 pt-4"><p class="text-xs text-white/50">02</p><p class="mt-2 font-semibold">Pick a time</p><p class="mt-2 text-sm leading-6 text-white/65">Select an available session from the room schedule.</p></div>
            <div class="border-t border-white/25 pt-4"><p class="text-xs text-white/50">03</p><p class="mt-2 font-semibold">Confirm</p><p class="mt-2 text-sm leading-6 text-white/65">Add your study purpose and finish the booking.</p></div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-white">
    <div class="mx-auto flex max-w-6xl flex-col gap-2 px-5 py-7 text-sm text-muted sm:flex-row sm:items-center sm:justify-between lg:px-8">
      <p>© 2026 StudySpace · University Library</p>
      <p>Room booking for focused study.</p>
    </div>
  </footer>
</body>
</html>
