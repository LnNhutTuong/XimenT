<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'XimenT - Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 h-screen overflow-hidden">
    {{-- Sidebar --}}
    @include('admin.layouts.sidebar')

    {{-- Main content area --}}
    <main class="ml-[260px] h-screen flex flex-col">
        {{-- Header --}}
        @include('admin.layouts.header')

        {{-- Page Content (Scrollable) --}}
        <div class="flex-grow overflow-y-auto p-8">
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('admin.layouts.footer')
    </main>
    @vite('resources/js/admin/common.js')
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: '{{ session("success") }}',
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: '{{ session("error") }}',
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại',
                    text: 'Vui lòng kiểm tra lại thông tin nhập vào!',
                });
            @endif
        });
    </script>
</body>
</html>
