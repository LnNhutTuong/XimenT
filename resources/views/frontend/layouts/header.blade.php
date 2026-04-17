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
        </ul>

        <div class="title">
            <a href="{{ route('home') }}">XimenT</a>  
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
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="btn-base btn-black flex items-center focus:outline-none transition border-none bg-transparent cursor-pointer">
                                <span class="btn-text">Xin chào, {{ Auth::user()->name }}</span>
                                <svg class="ms-2 size-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                                <span class="btn-underline"></span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="flex flex-col p-1 space-y-1">
                                <!-- Quản trị (Dành cho Admin) -->
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <x-dropdown-link href="{{ route('admin.dashboard') }}" class="!p-0 hover:!bg-transparent">
                                            <div class="btn-base btn-black w-full justify-start !mt-0">
                                                <span class="btn-text text-red-500">Quản trị</span>
                                                <span class="btn-underline bg-red-500"></span>
                                            </div>
                                        </x-dropdown-link>
                                    @endif
                                @endauth

                                <!-- Hồ sơ cá nhân -->
                                <x-dropdown-link href="{{ route('profile.show') }}" class="!p-0 hover:!bg-transparent">
                                    <div class="btn-base btn-black w-full justify-start !mt-0">
                                        <span class="btn-text">Hồ sơ cá nhân</span>
                                        <span class="btn-underline"></span>
                                    </div>
                                </x-dropdown-link>

                                <div class="border-t border-gray-100 my-1"></div>

                                <!-- Đăng xuất -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="!p-0 hover:!bg-transparent">
                                        <div class="btn-base btn-black w-full justify-start !mt-0">
                                            <span class="btn-text">Đăng xuất</span>
                                            <span class="btn-underline"></span>
                                        </div>
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endguest
            </div>
        </div>
    </nav>
</header>
