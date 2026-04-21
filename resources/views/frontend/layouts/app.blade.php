<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>
<body>
    <header class="w-full bg-white border-b pb-3">
        @include('frontend.layouts.header')
    </header>

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="bg-black text-white py-4 px-3 mt-16">
        @include('frontend.layouts.footer')
    </footer>
@stack('scripts')
    @livewireScripts
</body>
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
                    title: 'Lỗi hệ thống',
                    text: '{{ session("error") }}',
                    confirmButtonColor: '#000000ff'
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
 
</html>
