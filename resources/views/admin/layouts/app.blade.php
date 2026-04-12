<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'XimenT - Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    {{-- Sidebar --}}
    @include('admin.layouts.sidebar')

    {{-- Main content area (offset by sidebar width) --}}
    <main class="flex-grow flex flex-col ml-[260px]">
        {{-- Header --}}
        @include('admin.layouts.header')

        {{-- Page Content --}}
        <section class="p-8 flex-grow">
            @yield('content')
        </section>
        @include('admin.layouts.footer')
    </main>

    @vite('resources/js/admin/common.js')
    @stack('scripts')
</body>

</html>
