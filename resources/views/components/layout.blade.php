<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Google Material Design Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    @stack('styles')
    <livewire:styles />

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</head>
<body class="antialiased">
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">TfL Auto Calendar</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                {{ $title }}
            </p>
            <div class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                {{ $intro }}
            </div>
        </div>
        <div class="mt-10">
            {{ $slot }}
        </div>
    </div>
    @if(Route::currentRouteName() !== 'home')
        <a href="{{ route('home') }}"
           class="absolute left-4 top-9 flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
            <span class="material-icons-outlined h-6 w-6">home</span>
        </a>
    @endif
</div>
<!-- Scripts -->
<livewire:scripts />
@stack('javascript')
</body>
</html>
