<header class="w-full bg-white border-b">
    <nav class="container mx-auto px-4 h-16 grid grid-cols-3 items-center text-black">
        <ul>
            <x-nav-link :href="route('home')" class="btn-base btn-black">
                <span class="btn-text">Trang chủ</span>
                <span class="btn-underline"></span>
            </x-nav-link>

            <x-nav-link :href="route('product')" class="btn-base btn-black">
                <span class="btn-text">Sản phẩm</span>
                <span class="btn-underline"></span>
            </x-nav-link>

            @auth
                @if(auth()->user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" class="btn-base btn-black">
                        <span class="btn-text" style="color: red;">Quản trị</span>
                        <span class="btn-underline"></span>
                    </x-nav-link>
                @endif
            @endauth
        </ul>

        <div class="title">
            XimenT  
        </div>

        <div class="flex justify-end">
            <div class="flex items-center space-x-3">

                @guest
                    {{-- Chưa đăng nhập: hiện nút Đăng nhập và Đăng ký --}}
                    <a href="{{ route('login') }}" class="btn-base btn-black">
                        <span class="btn-text">Đăng nhập</span>
                        <span class="btn-underline"></span>
                    </a>

                    <a href="{{ route('register') }}" class="btn-base btn-black">
                        <span class="btn-text">Đăng ký</span>
                        <span class="btn-underline"></span>
                    </a>
                @else
                    {{-- Đã đăng nhập: hiện tên và nút Đăng xuất --}}
                    <a href="{{ route('dashboard') }}" class="btn-base btn-black">
                        <span class="btn-text">{{ auth()->user()->name }}</span>
                        <span class="btn-underline"></span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-base btn-black">
                            <span class="btn-text">Đăng xuất</span>
                            <span class="btn-underline"></span>
                        </button>
                    </form>
                @endguest

            </div>
        </div>
    </nav>
</header>
