<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $room->name }} — StudySpace</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={theme:{extend:{colors:{navy:'#202724',blue:'#173A34',sky:'#DCE7E1',mist:'#F6F3EC',line:'#D9DDD8',muted:'#66716C',pine:'#173A34',paper:'#F6F3EC',sage:'#DCE7E1'}}}}</script>
</head>
<body class="bg-mist text-navy antialiased">
  <header class="border-b border-line bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
      <a href="{{ route('home') }}" class="flex items-center gap-3 font-semibold"><span class="grid h-10 w-10 place-items-center rounded-2xl bg-navy text-white"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M4 5.5v15A2.5 2.5 0 0 1 6.5 18H20"/></svg></span>StudySpace</a>
      <div class="flex items-center gap-2"><a href="{{ route('bookings.index') }}" class="rounded-xl px-4 py-2 text-sm font-medium text-muted hover:bg-slate-100 hover:text-navy">My bookings</a>@guest<a href="{{ route('login') }}" class="rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white">Log in</a>@else<span class="text-sm">{{ auth()->user()->name }}</span>@endguest</div>
    </div>
  </header>

  <main class="mx-auto max-w-7xl px-5 py-8 lg:px-8 lg:py-12">
    <a href="{{ route('home') }}#rooms" class="inline-flex items-center gap-2 text-sm font-medium text-muted hover:text-navy">← Back to rooms</a>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1.15fr_.85fr]">
      <section>
        <div class="overflow-hidden rounded-[1.75rem] bg-slate-200"><img src="{{ $room->image_url ?: 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1400&q=85' }}" alt="{{ $room->name }}" class="h-[360px] w-full object-cover sm:h-[460px]"></div>
        <div class="mt-7 flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
          <div><div class="flex items-center gap-2 text-sm font-semibold text-blue"><span>{{ $room->building }}</span><span class="text-slate-300">•</span><span>{{ $room->floor }}{{ $room->location ? ' · '.$room->location : '' }}</span></div><h1 class="mt-2 text-4xl font-semibold tracking-[-0.035em] sm:text-5xl">{{ $room->name }}</h1><p class="mt-4 max-w-2xl leading-7 text-muted">{{ $room->description }}</p></div>
          <span class="w-fit rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">{{ $slots->contains('available', true) ? 'Available' : 'No times available' }}</span>
        </div>

        <div class="mt-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
          <div class="rounded-2xl border border-line bg-white p-4"><p class="text-xs font-semibold uppercase tracking-wider text-muted">Capacity</p><p class="mt-2 text-lg font-semibold">{{ $room->capacity }} people</p></div>
          <div class="rounded-2xl border border-line bg-white p-4"><p class="text-xs font-semibold uppercase tracking-wider text-muted">Category</p><p class="mt-2 text-lg font-semibold">{{ $room->category }}</p></div>
          <div class="rounded-2xl border border-line bg-white p-4"><p class="text-xs font-semibold uppercase tracking-wider text-muted">Equipment</p><p class="mt-2 text-lg font-semibold">{{ implode(', ', $room->equipment ?? []) ?: 'Not listed' }}</p></div>
          <div class="rounded-2xl border border-line bg-white p-4"><p class="text-xs font-semibold uppercase tracking-wider text-muted">Status</p><p class="mt-2 text-lg font-semibold">{{ $room->is_available ? 'Available' : 'Unavailable' }}</p></div>
        </div>

        <div class="mt-8 rounded-3xl border border-line bg-white p-6"><h2 class="text-lg font-semibold">Room guidelines</h2><div class="mt-4 grid gap-3 text-sm text-muted sm:grid-cols-2"><p>• Maximum booking: 2 hours</p><p>• Arrive within 15 minutes</p><p>• Food is not allowed</p><p>• Leave the room tidy</p></div></div>
      </section>

      <aside class="h-fit rounded-3xl border border-line bg-white p-5 shadow-sm lg:sticky lg:top-6 sm:p-6">
        <div class="flex items-center justify-between"><div><p class="text-sm font-semibold text-blue">Reserve {{ $room->name }}</p><h2 class="mt-1 text-2xl font-semibold">Choose your time</h2></div><span class="grid h-11 w-11 place-items-center rounded-2xl bg-sky text-blue"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 2v3m8-3v3M3 9h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2z"/></svg></span></div>

        <form id="dateForm" method="GET" action="{{ route('rooms.show', $room) }}">
          <label class="mt-6 block text-sm font-semibold" for="bookingDate">Booking date</label>
          <input id="bookingDate" name="date" type="date" value="{{ $selectedDate }}" min="{{ today()->toDateString() }}" class="mt-2 w-full rounded-2xl border border-line bg-white px-4 py-3 text-sm outline-none focus:border-blue focus:ring-4 focus:ring-blue/10" />
          <button type="submit" class="mt-2 text-sm font-semibold text-blue">Check this date</button>
        </form>

        <div class="mt-6 flex items-center justify-between"><label class="text-sm font-semibold">Available slots</label><span class="text-xs text-muted">90 min each</span></div>
        <div id="timeSlots" class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3">
          @foreach ($slots as $slot)
            @if ($slot['available'])
              <button type="button" data-time="{{ $slot['time'] }}" data-label="{{ $slot['label'] }}" class="slot rounded-2xl border border-line bg-white px-3 py-3 text-sm font-medium transition hover:border-blue hover:text-blue">{{ $slot['label'] }}</button>
            @else
              <button type="button" disabled class="rounded-2xl border border-line bg-slate-50 px-3 py-3 text-sm font-medium text-slate-300 line-through">{{ $slot['label'] }}</button>
            @endif
          @endforeach
        </div>
        @error('slot')<p class="mt-3 text-sm text-red-700">{{ $message }}</p>@enderror

        <div class="mt-6 rounded-2xl bg-slate-50 p-4">
          <div class="flex items-center justify-between text-sm"><span class="text-muted">Selected date</span><span id="summaryDate" class="font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('M j, Y') }}</span></div>
          <div class="mt-3 flex items-center justify-between text-sm"><span class="text-muted">Selected time</span><span id="summaryTime" class="font-semibold text-slate-400">Choose a slot</span></div>
          <div class="mt-3 flex items-center justify-between text-sm"><span class="text-muted">Duration</span><span class="font-semibold">1 hr 30 min</span></div>
        </div>

        <p id="slotHint" class="mt-4 text-xs leading-5 text-muted">Select one available time slot to continue.</p>
        <a id="continueBtn" data-booking-url="{{ route('bookings.create', $room) }}" href="#" aria-disabled="true" class="pointer-events-none mt-4 block rounded-2xl bg-slate-200 px-4 py-3.5 text-center text-sm font-semibold text-slate-400">Continue to booking</a>
      </aside>
    </div>
  </main>

  <script>
    const dateInput = document.getElementById('bookingDate');
    const slots = document.querySelectorAll('.slot');
    const summaryDate = document.getElementById('summaryDate');
    const summaryTime = document.getElementById('summaryTime');
    const continueBtn = document.getElementById('continueBtn');
    const slotHint = document.getElementById('slotHint');
    const dateForm = document.getElementById('dateForm');
    let selectedTime = '';
    let selectedLabel = '';

    function formatDate(value) {
      if (!value) return 'Choose a date';
      return new Date(value + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function endTime(time) {
      const [h, m] = time.split(':').map(Number);
      const d = new Date(2000, 0, 1, h, m + 90);
      return d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    }

    function refreshContinue() {
      summaryDate.textContent = formatDate(dateInput.value);
      if (!selectedTime || !dateInput.value) {
        continueBtn.href = '#';
        continueBtn.setAttribute('aria-disabled', 'true');
        continueBtn.className = 'pointer-events-none mt-4 block rounded-2xl bg-slate-200 px-4 py-3.5 text-center text-sm font-semibold text-slate-400';
        return;
      }
      const range = `${selectedLabel}–${endTime(selectedTime)}`;
      summaryTime.textContent = range;
      summaryTime.classList.remove('text-slate-400');
      continueBtn.href = `${continueBtn.dataset.bookingUrl}?date=${encodeURIComponent(dateInput.value)}&time=${encodeURIComponent(selectedTime)}`;
      continueBtn.removeAttribute('aria-disabled');
      continueBtn.className = 'mt-4 block rounded-2xl bg-blue px-4 py-3.5 text-center text-sm font-semibold text-white transition hover:bg-blue-700';
      slotHint.textContent = 'Your selected slot will be carried to the booking form.';
    }

    slots.forEach(btn => {
      btn.addEventListener('click', () => {
        slots.forEach(slot => slot.className = 'slot rounded-2xl border border-line bg-white px-3 py-3 text-sm font-medium transition hover:border-blue hover:text-blue');
        btn.className = 'slot rounded-2xl border border-blue bg-blue px-3 py-3 text-sm font-semibold text-white shadow-sm';
        selectedTime = btn.dataset.time;
        selectedLabel = btn.dataset.label;
        refreshContinue();
      });
    });

    dateInput.addEventListener('change', () => dateForm.requestSubmit());
    refreshContinue();
  </script>
</body>
</html>
