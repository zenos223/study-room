<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log in — StudySpace</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper text-ink antialiased">
  <main class="min-h-screen lg:grid lg:grid-cols-[.78fr_1.22fr]">
    <aside class="hidden bg-pine p-10 text-white lg:flex lg:flex-col lg:justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <span class="grid h-9 w-9 place-items-center border border-white/30">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5.5h11a3 3 0 0 1 3 3V20H7a3 3 0 0 0-3 3z"/><path d="M4 5.5V23"/></svg>
        </span>
        <div><p class="text-sm font-semibold">StudySpace</p><p class="text-[10px] uppercase tracking-[0.18em] text-white/55">University Library</p></div>
      </a>

      <div class="max-w-sm">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/55">Student access</p>
        <h1 class="mt-4 text-4xl font-semibold leading-tight tracking-[-0.035em]">Your study room, when you need it.</h1>
        <p class="mt-5 text-sm leading-7 text-white/65">Sign in to reserve rooms, check upcoming sessions, and manage your bookings.</p>

        <div class="mt-8 border-y border-white/15 py-6">
          <div class="grid grid-cols-[44px_1fr] gap-4">
            <div class="grid h-11 w-11 place-items-center border border-white/20 text-white/75">
              <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 19V7l8-4 8 4v12"/><path d="M8 19v-5h8v5M3 19h18"/></svg>
            </div>
            <div><p class="font-semibold">Library hours</p><p class="mt-1 text-sm text-white/60">Monday–Friday · 8:00 AM–9:00 PM</p></div>
          </div>
        </div>
      </div>

      <p class="text-xs text-white/45">University Library · Room Booking Service</p>
    </aside>

    <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
      <div class="w-full max-w-md">
        <div class="mb-10 flex items-center justify-between lg:hidden">
          <a href="{{ route('home') }}" class="font-semibold text-pine">StudySpace</a>
          <a href="{{ route('home') }}" class="text-sm text-muted hover:text-ink">Back to rooms</a>
        </div>

        <p class="text-xs font-bold uppercase tracking-[0.18em] text-pine">Welcome back</p>
        <h2 class="mt-3 text-3xl font-semibold tracking-[-0.03em]">Log in to your account</h2>
        <p class="mt-3 text-sm leading-6 text-muted">Use your university email to continue.</p>

        <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-5">
          @csrf
          <label class="block">
            <span class="text-sm font-medium">University email</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@university.edu" required autofocus autocomplete="username" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none transition focus:border-pine focus:ring-1 focus:ring-pine" />
            @error('email')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="block">
            <div class="flex items-center justify-between"><span class="text-sm font-medium">Password</span><span class="text-xs font-semibold text-pine">Forgot password?</span></div>
            <input type="password" name="password" placeholder="Enter your password" required autocomplete="current-password" class="mt-2 w-full border border-line bg-white px-4 py-3 text-sm outline-none transition focus:border-pine focus:ring-1 focus:ring-pine" />
            @error('password')<span class="mt-1 block text-sm text-red-700">{{ $message }}</span>@enderror
          </label>

          <label class="flex items-center gap-3 text-sm text-muted">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 border-line text-pine focus:ring-pine" />
            Keep me signed in on this device
          </label>

          <button type="submit" class="w-full bg-pine px-5 py-3 text-sm font-semibold text-white hover:bg-[#102e29]">Log in</button>
        </form>

        <div class="mt-7 border-t border-line pt-6 text-sm text-muted">
          New to StudySpace? <a href="{{ route('register') }}" class="font-semibold text-pine hover:underline">Create a student account</a>
        </div>

        <p class="mt-8 text-xs leading-5 text-muted">Need help accessing your account? Contact the library service desk.</p>
      </div>
    </section>
  </main>
</body>
</html>
