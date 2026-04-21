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

             <x-nav-link :href="route('brand')" class="btn-base btn-black">
                <span class="btn-text">Thương hiệu</span>
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
                    <div class="flex items-center space-x-2">
                        <!-- Icon Giỏ Hàng -->
                        <button type="button" onclick="window.location.href='{{ route('cart') }}'" class="relative text-black hover:opacity-70 transition mr-2 mt-3 cursor-pointer bg-transparent border-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            @if(Auth::check() && Auth::user()->cart && Auth::user()->cart->items->sum('quantity') > 0)
                                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full">
                                    {{ Auth::user()->cart->items->sum('quantity') }}
                                </span>
                            @endif
                        </button>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button" 
                                    class="btn-base btn-black mt-4 focus:outline-none transition border-none bg-transparent cursor-pointer">
                                <span class="btn-text mb-1">Xin chào, {{ Auth::user()->name }}</span>
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

                                <!-- Đơn hàng của tôi -->
                                <x-dropdown-link href="{{ route('account.orders') }}" class="!p-0 hover:!bg-transparent">
                                    <div class="btn-base btn-black w-full justify-start !mt-0">
                                        <span class="btn-text">Theo dõi đơn hàng</span>
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
                    </div>
                @endguest
            </div>
        </div>
    </nav>
