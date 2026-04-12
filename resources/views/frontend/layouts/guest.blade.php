<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <main class="min-h-screen">
        <div class="flex justify-end">
            <a href="{{route('home')}}" class=" btn-base btn-black" ">
            <span class="btn-text "> <-- Trở về trang chủ</span>
            <span class="btn-underline"></span>
        </a>
        </div>
        
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>
