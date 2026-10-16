<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account — StudySpace</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-ink antialiased">
  <header class="border-b border-line bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 lg:px-8">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <span class="grid h-9 w-9 place-items-center bg-pine text-white">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5.5h11a3 3 0 0 1 3 3V20H7a3 3 0 0 0-3 3z"/><path d="M4 5.5V23"/></svg>
        </span>
        <div><p class="text-sm font-semibold">StudySpace</p><p class="text-[10px] uppercase tracking-[0.18em] text-muted">University Library</p></div>
      </a>
      <p class="text-sm text-muted">Already registered? <a href="{{ route('login') }}" class="font-semibold text-pine hover:underline">Log in</a></p>
    </div>
  </header>

  <main class="mx-auto grid max-w-6xl gap-10 px-5 py-10 lg:grid-cols-[1fr_.65fr] lg:px-8 lg:py-14">
    <section>
      <p class="text-xs font-bold uppercase tracking-[0.18em] text-pine">Student registration</p>
      <h1 class="mt-3 text-3xl font-semibold tracking-[-0.03em] sm:text-4xl">Create your StudySpace account</h1>
      <p class="mt-3 max-w-2xl text-sm leading-6 text-muted">Register with your university details. Your account will be used to reserve rooms and manage your study sessions.</p>

      <form action="{{ route('register.store') }}" method="POST" class="mt-9 border border-line bg-paper p-5 sm:p-7">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
          <label class="block sm:col-span-2">
            <span class="text-sm font-medium">Full name</span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required autocomplete="name" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none focus:border-pine focus:ring-1 focus:ring-pine" />
            @error('name')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="text-sm font-medium">Your Birthday</span>
            <input type="text" name="student_id" value="{{ old('student_id') }}" placeholder="Your Birthday Date" required class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none focus:border-pine focus:ring-1 focus:ring-pine" />
            @error('student_id')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="text-sm font-medium">Department</span>
            <select name="department" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none focus:border-pine focus:ring-1 focus:ring-pine">
              <option value="">Select department</option>
              @foreach (['Computer Science', 'Business', 'Engineering', 'Arts & Humanities'] as $department)
                <option value="{{ $department }}" @selected(old('department') === $department)>{{ $department }}</option>
              @endforeach
            </select>
            @error('department')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="block sm:col-span-2">
            <span class="text-sm font-medium">University email</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu" required autocomplete="email" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none focus:border-pine focus:ring-1 focus:ring-pine" />
            @error('email')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="text-sm font-medium">Password</span>
            <input type="password" name="password" placeholder="Create a password" required autocomplete="new-password" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none focus:border-pine focus:ring-1 focus:ring-pine" />
            @error('password')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <span class="text-sm font-medium">Confirm password</span>
            <input type="password" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none focus:border-pine focus:ring-1 focus:ring-pine" />
          </label>
        </div>

        <label class="mt-6 flex items-start gap-3 text-sm leading-6 text-muted">
          <input type="checkbox" name="agreement" value="1" required class="mt-1 h-4 w-4 border-line text-pine focus:ring-pine" />
          <span>I agree to follow the library's room booking and study-space guidelines.</span>
        </label>

        <button type="submit" class="mt-6 bg-pine px-6 py-3 text-sm font-semibold text-white hover:bg-[#102e29]">Create account</button>
      </form>
    </section>

    <aside class="h-fit border-l border-line pl-0 lg:pl-10">
      <div class="bg-pine p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/55">What you can do</p>
        <div class="mt-6 space-y-6">
          <div class="grid grid-cols-[32px_1fr] gap-3"><span class="text-sm text-white/45">01</span><div><p class="font-semibold">Reserve rooms</p><p class="mt-1 text-sm leading-6 text-white/65">Choose from quiet, group, and technology-enabled spaces.</p></div></div>
          <div class="grid grid-cols-[32px_1fr] gap-3"><span class="text-sm text-white/45">02</span><div><p class="font-semibold">Manage your schedule</p><p class="mt-1 text-sm leading-6 text-white/65">Review upcoming bookings and cancel when plans change.</p></div></div>
          <div class="grid grid-cols-[32px_1fr] gap-3"><span class="text-sm text-white/45">03</span><div><p class="font-semibold">See room availability</p><p class="mt-1 text-sm leading-6 text-white/65">Check open time slots before heading to the library.</p></div></div>
        </div>
      </div>

      <div class="border border-t-0 border-line bg-paper p-6">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Booking reminder</p>
        <p class="mt-3 text-sm leading-6 text-muted">Rooms are shared university resources. Please arrive on time and leave the room ready for the next student.</p>
      </div>
    </aside>
  </main>
</body>
</html>
