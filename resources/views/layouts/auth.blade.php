<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root { color-scheme: light dark; font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; color: #1b1b18; background: #fdfdfc; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; padding: 24px; display: grid; place-items: center; background: #fdfdfc; }
        main { width: min(100%, 440px); padding: 32px; border: 1px solid #e3e3e0; border-radius: 8px; background: #fff; box-shadow: 0 8px 28px #1b1b1812; }
        h1 { margin: 0 0 8px; font-size: 26px; font-weight: 600; }
        p { color: #706f6c; line-height: 1.5; }
        label { display: block; margin: 18px 0 6px; font-size: 14px; font-weight: 500; }
        input:not([type="checkbox"]) { width: 100%; min-height: 42px; padding: 9px 11px; border: 1px solid #b9b9b2; border-radius: 4px; background: #fff; color: #1b1b18; font: inherit; }
        input:focus { outline: 2px solid #f53003; outline-offset: 2px; }
        button { min-height: 42px; margin-top: 20px; padding: 9px 16px; border: 1px solid #1b1b18; border-radius: 4px; background: #1b1b18; color: #fff; font: inherit; cursor: pointer; }
        a { color: #c42c0a; }
        .error { margin: 5px 0 0; color: #b42318; font-size: 13px; }
        .links { margin-top: 22px; font-size: 14px; }
        .remember { display: flex; align-items: center; gap: 8px; margin-top: 16px; font-size: 14px; }
        @media (prefers-color-scheme: dark) {
            :root { color: #ededec; background: #0a0a0a; }
            body { background: #0a0a0a; }
            main { border-color: #3e3e3a; background: #161615; }
            p { color: #a1a09a; }
            input:not([type="checkbox"]) { border-color: #62605b; background: #0a0a0a; color: #ededec; }
            button { border-color: #ededec; background: #ededec; color: #1c1c1a; }
            a { color: #ff755f; }
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>