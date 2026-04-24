<nav :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="bg-[#09090a] shadow-2xl border-r border-gray-800 h-screen fixed top-0 left-0 min-w-[260px] py-6 px-4 overflow-y-auto overflow-x-hidden custom-scrollbar transition-transform duration-300 z-50 md:translate-x-0">
    {{-- Header Profile --}}
    <div class="flex items-center px-4 mb-8">
        <div class="relative">
            <img src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=6366f1&color=fff' class="w-11 h-11 rounded-full border-2 border-gray-800 shadow-md transition-transform hover:scale-105" />
            <span class="h-3.5 w-3.5 rounded-full bg-emerald-500 border-2 border-gray-900 block absolute bottom-0 right-0"></span>
        </div>
        <div class="ml-4 truncate">
            <p class="text-xl font-bold text-white tracking-wide truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
        </div>
    </div>

    {{-- Menu Items --}}
    <ul class="space-y-2">
        {{-- Trang chủ --}}
        <li>
            <a href="{{ route('admin.dashboard') }}" class="group text-gray-400 hover:text-white hover:bg-white/5
                     {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-indigo-400 font-semibold' : '' }} 
                    transition-all duration-300 text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] mr-3 transition-transform group-hover:scale-110">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Trang chủ</span>
            </a>
        </li>
    </ul>

    @php
        $isShopActive = request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.products.*');
    @endphp

    {{-- Quản lý sản phẩm --}}
    <div class="mt-8 mb-2">
        <h6 class="text-[10px] uppercase font-extrabold text-gray-500 px-4 mb-3 tracking-[0.15em]">Cửa hàng</h6>
        
        <div class="flex items-center cursor-pointer group collapsible-toggle hover:bg-white/5 rounded-xl px-4 py-3 transition-all duration-300 {{ $isShopActive ? 'bg-white/5 text-white' : 'text-gray-400 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] mr-3 transition-transform group-hover:scale-110 {{ $isShopActive ? 'text-indigo-400' : '' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
            <span class="text-[14px] font-medium flex-1">Quản lý sản phẩm</span>
            <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="arrow w-4 h-4 transition-transform duration-300 {{ $isShopActive ? 'rotate-90 text-indigo-400' : '' }}" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </div>

        <ul class="space-y-1 mt-1.5 pl-6 overflow-hidden transition-all duration-300 {{ $isShopActive ? 'max-h-[500px]' : 'max-h-0' }}">
            <li class="relative border-l border-gray-700 ml-4 pl-4 before:absolute before:w-3 before:h-px before:left-0 before:top-1/2 before:-translate-y-1/2 before:bg-gray-700 hover:before:bg-indigo-400 before:transition-colors">
                <a href="{{ route('admin.categories.index') }}" class="group flex items-center text-[13px] py-2 rounded-lg px-3 transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-500/10 text-indigo-400 font-semibold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-2 transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.8)]' : 'bg-gray-600 group-hover:bg-gray-400' }}"></span>
                    Danh mục
                </a>
            </li>
            <li class="relative border-l border-gray-700 ml-4 pl-4 before:absolute before:w-3 before:h-px before:left-0 before:top-1/2 before:-translate-y-1/2 before:bg-gray-700 hover:before:bg-indigo-400 before:transition-colors">
                <a href="{{ route('admin.brands.index') }}" class="group flex items-center text-[13px] py-2 rounded-lg px-3 transition-all duration-200 {{ request()->routeIs('admin.brands.*') ? 'bg-indigo-500/10 text-indigo-400 font-semibold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-2 transition-all duration-300 {{ request()->routeIs('admin.brands.*') ? 'bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.8)]' : 'bg-gray-600 group-hover:bg-gray-400' }}"></span>
                    Thương hiệu
                </a>
            </li>
            <li class="relative border-l border-gray-700 ml-4 pl-4 before:absolute before:w-3 before:h-px before:left-0 before:top-1/2 before:-translate-y-1/2 before:bg-gray-700 hover:before:bg-indigo-400 before:transition-colors">
                <a href="{{ route('admin.products.index') }}" class="group flex items-center text-[13px] py-2 rounded-lg px-3 transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-500/10 text-indigo-400 font-semibold' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-2 transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.8)]' : 'bg-gray-600 group-hover:bg-gray-400' }}"></span>
                    Sản phẩm
                </a>
            </li>
        </ul>
    </div>

    {{-- Khách hàng & Đơn hàng --}}
    <ul class="space-y-2">
        <h6 class="text-[10px] uppercase font-extrabold text-gray-500 px-4 mt-8 mb-3 tracking-[0.15em]">Kinh doanh</h6>
        <li>
            <a href="{{ route('admin.customers.index') }}" class="group text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-300 text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] mr-3 transition-transform group-hover:scale-110">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span>Quản lý khách hàng</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}" class="group text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-300 text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] mr-3 transition-transform group-hover:scale-110">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <span>Quản lý đơn hàng</span>
            </a>
        </li>
    </ul>

    {{-- Logout --}}
    <div class="mt-10 pt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="group w-full text-red-400 hover:text-red-300 hover:bg-red-500/10 font-bold transition-all duration-300 text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] mr-3 transition-transform group-hover:-translate-x-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                </svg>
                <span>Đăng xuất</span>
            </button>
        </form>
    </div>

</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".collapsible-toggle").forEach((toggle) => {
            toggle.addEventListener("click", function () {
                let menu = this.nextElementSibling;
                let arrowIcon = this.querySelector(".arrow");

                if (menu.style.maxHeight && menu.style.maxHeight !== '0px') {
                    menu.style.maxHeight = '0px';
                    arrowIcon.classList.remove("rotate-90");
                } else {
                    menu.style.maxHeight = menu.scrollHeight + "px";
                    arrowIcon.classList.add("rotate-90");
                }
            });
        });
    });
</script>

<style>
/* Custom scrollbar for dark sidebar */
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #1f2937;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #374151;
}
</style>
