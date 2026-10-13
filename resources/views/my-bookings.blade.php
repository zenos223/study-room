<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Bookings — StudySpace</title>
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
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M4 5.5h11a3 3 0 0 1 3 3V20H7a3 3 0 0 0-3 3z"/>
            <path d="M4 5.5V23"/>
          </svg>
        </span>
        <div>
          <p class="text-sm font-semibold tracking-tight">StudySpace</p>
          <p class="text-[10px] uppercase tracking-[0.18em] text-muted">Library Room Booking</p>
        </div>
      </a>

      <nav class="hidden items-center gap-7 text-sm md:flex">
        <a href="{{ route('home') }}#rooms" class="text-muted hover:text-ink">Rooms</a>
        <a href="{{ route('bookings.index') }}" class="font-medium text-pine">My bookings</a>
        <a href="{{ route('home') }}#how" class="text-muted hover:text-ink">How it works</a>
      </nav>

      <div class="flex items-center gap-2">
        <span class="px-3 py-2 text-sm text-muted">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="border border-pine bg-pine px-4 py-2 text-sm font-semibold text-white hover:bg-[#102e29]">Log out</button></form>
      </div>
    </div>
  </header>

  <main class="mx-auto max-w-6xl px-5 py-10 lg:px-8 lg:py-14">
    <section class="border-b border-line pb-7">
      <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-pine">Student schedule</p>
          <h1 class="mt-2 text-3xl font-semibold tracking-[-0.03em] sm:text-4xl">My bookings</h1>
          <p class="mt-3 max-w-xl text-sm leading-6 text-muted">Review your upcoming study-room reservations and keep your library schedule organized.</p>
        </div>
        <a href="{{ route('home') }}#rooms" class="w-fit bg-pine px-5 py-3 text-sm font-semibold text-white hover:bg-[#102e29]">Book another room</a>
      </div>
    </section>

    <section class="py-7">
      <div class="flex flex-wrap gap-2 border-b border-line pb-5">
        <a href="{{ route('bookings.index', ['filter' => 'upcoming']) }}" class="{{ $filter === 'upcoming' ? 'border-pine bg-pine text-white' : 'border-line bg-white text-muted hover:border-pine hover:text-pine' }} border px-4 py-2 text-sm font-semibold">Upcoming</a>
        <a href="{{ route('bookings.index', ['filter' => 'past']) }}" class="{{ $filter === 'past' ? 'border-pine bg-pine text-white' : 'border-line bg-white text-muted hover:border-pine hover:text-pine' }} border px-4 py-2 text-sm font-medium">Past</a>
        <a href="{{ route('bookings.index', ['filter' => 'cancelled']) }}" class="{{ $filter === 'cancelled' ? 'border-pine bg-pine text-white' : 'border-line bg-white text-muted hover:border-pine hover:text-pine' }} border px-4 py-2 text-sm font-medium">Cancelled</a>
      </div>

      <div class="mt-6 space-y-4">
        @forelse ($bookings as $booking)
        @php($startsAt = $booking->starts_at)
        <article class="border border-line bg-white">
          <div class="grid gap-0 md:grid-cols-[120px_1fr_auto] md:items-stretch">
            <div class="flex items-center justify-center border-b border-line bg-sage p-5 text-pine md:border-b-0 md:border-r">
              <div class="text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em]">{{ $startsAt->format('M') }}</p>
                <p class="mt-1 text-3xl font-semibold">{{ $startsAt->format('d') }}</p>
                <p class="mt-1 text-xs font-medium">{{ $startsAt->format('l') }}</p>
              </div>
            </div>

            <div class="p-5 sm:p-6">
              <div class="flex flex-wrap items-center gap-3">
                <h2 class="text-xl font-semibold">{{ $booking->room->name }}</h2>
                <span class="border border-pine/20 bg-sage px-2.5 py-1 text-xs font-semibold">{{ ucfirst($booking->status) }}</span>
              </div>
              <p class="mt-3 text-sm font-semibold">{{ $startsAt->format('g:i A') }}–{{ $booking->ends_at->format('g:i A') }}</p>
              <p class="mt-2 text-sm text-muted">{{ $booking->room->building }} · {{ $booking->room->floor }} · {{ $booking->purpose }}</p>

              <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-muted">
                <span>{{ $booking->room->capacity }} seats</span>
                @foreach ($booking->room->equipment ?? [] as $equipment)<span>{{ $equipment }}</span>@endforeach
              </div>
            </div>

            <div class="flex items-center gap-2 border-t border-line p-5 md:flex-col md:justify-center md:border-l md:border-t-0">
              <a href="{{ route('rooms.show', $booking->room) }}" class="border border-line bg-white px-4 py-2.5 text-center text-sm font-semibold hover:border-pine hover:text-pine">View room</a>
              @if ($booking->status !== 'cancelled' && $booking->starts_at->isFuture())
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}">@csrf<button class="border border-line bg-paper px-4 py-2.5 text-sm font-semibold text-muted hover:border-red-300 hover:text-red-600">Cancel</button></form>
              @endif
            </div>
          </div>
        </article>
        @empty
          <p class="border border-line bg-white p-6 text-sm text-muted">No bookings in this section yet.</p>
        @endforelse
      </div>
      <div class="mt-6">{{ $bookings->links() }}</div>
    </section>

    <aside class="border-t border-line py-6 text-sm text-muted">
      Need to change a reservation? Cancel it here and choose a new available time from the room directory.
    </aside>
  </main>
</body>
</html>
