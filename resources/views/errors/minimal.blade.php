<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Basic --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Title --}}
    <title>@yield('title') | {{ config('app.name') }}</title>

    {{-- SEO Control (Critical for Error Pages) --}}
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">

    {{-- Description (Optional but Clean) --}}
    <meta name="description" content="@yield('message', 'The page you are looking for could not be found or is temporarily unavailable.')">

    {{-- Canonical (Self-referencing to avoid confusion) --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('shwtshortlog.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('shwtshortlog.png') }}">

    {{-- Open Graph (Safe Defaults for Error Pages) --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('title') | {{ config('app.name') }}">
    <meta property="og:description" content="@yield('message', 'This page is currently unavailable.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('shwtshortlog.png') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title') | {{ config('app.name') }}">
    <meta name="twitter:description" content="@yield('message', 'This page is currently unavailable.')">
    <meta name="twitter:image" content="{{ asset('shwtshortlog.png') }}">

    {{-- Security & UX --}}
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#0d6efd">
    {{-- Google tag (gtag.js) --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PGZEP6FJBX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-PGZEP6FJBX');
    </script>
    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @livewireStyles --}}
</head>

<body
    class="font-sans bg-surface-light dark:bg-surface-dark text-neutral-text dark:text-neutral-text-dark transition-colors duration-300">

    {{-- Livewire Navbar --}}
    <livewire:navbar />

    {{-- Error Section --}}
    <section class="flex items-center justify-center min-h-[75vh] px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center max-w-6xl w-full">

            {{-- Illustration --}}
            <div class="flex justify-center">
                <img src="@yield('image', asset('storage/images/errors/undraw_bug-fixing_sgk7.svg'))" alt="@yield('title')" class="max-w-md w-full dark:opacity-90">
            </div>

            {{-- Error Content --}}
            <div class="text-center">

                {{-- <h1 class="text-7xl md:text-9xl font-extrabold btn-primary leading-none">
                    @yield('code')
                </h1> --}}

                <h2 class="mt-4 text-2xl md:text-4xl font-bold text-neutral-text dark:text-neutral-text-dark">
                    @yield('title')
                </h2>

                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-md text-center mx-auto">
                    @yield('message')
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-4 text-center justify-center">
                    <a href="{{ route('home') }}" class="btn-primary px-6 py-3 text-center rounded-lg">
                        Go to Home
                    </a>

                    <button onclick="history.back()" class="btn-warning px-6 py-3 rounded-lg transition">
                        Go Back
                    </button>
                </div>

            </div>
        </div>
    </section>

    {{-- Livewire Footer --}}
    <livewire:footer />

    {{-- @livewireScripts --}}
</body>

</html>
