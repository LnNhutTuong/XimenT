<nav class="bg-[#09090a] shadow-lg h-screen fixed top-0 left-0 min-w-[260px] py-6 px-4 overflow-y-auto overflow-x-hidden custom-scrollbar">
    {{-- Header Profile --}}
    <div class="flex items-center px-4 mb-8">
        <div class="relative">
            <img src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=2563eb&color=fff' class="w-11 h-11 rounded-full border-2 border-gray-700 shadow-sm" />
            <span class="h-3 w-3 rounded-full bg-green-500 border-2 border-[#09090a] block absolute bottom-0 right-0"></span>
        </div>
        <div class="ml-4 truncate">
            <p class="text-[15px] font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
            <p class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wider font-semibold">Admin Panel</p>
        </div>
    </div>

    {{-- Menu Items --}}
    <ul class="space-y-1.5">
        {{-- Trang chủ --}}
        <li>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white hover:bg-gray-800
                     {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white font-medium' : '' }} 
                    transition-all text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Trang chủ</span>
            </a>
        </li>
    </ul>

    {{-- Quản lý sản phẩm --}}
    <div class="mt-8 mb-2">
        <h6 class="text-[11px] uppercase font-bold text-gray-500 px-4 mb-2 tracking-wider">Cửa hàng</h6>
        
        <div class="flex items-center cursor-pointer group collapsible-toggle hover:bg-gray-800 rounded-xl px-4 py-3 transition-all text-gray-300 hover:text-white {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
            <span class="text-[14px] flex-1">Quản lý sản phẩm</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="arrow w-3 h-3 fill-current transition-all transform duration-200 {{ request()->routeIs('admin.products.*') ? 'rotate-90' : '' }}" viewBox="0 0 492.004 492.004">
                <path d="M382.678 226.804 163.73 7.86C158.666 2.792 151.906 0 144.698 0s-13.968 2.792-19.032 7.86l-16.124 16.12c-10.492 10.504-10.492 27.576 0 38.064L293.398 245.9l-184.06 184.06c-5.064 5.068-7.86 11.824-7.86 19.028 0 7.212 2.796 13.968 7.86 19.04l16.124 16.116c5.068 5.068 11.824 7.86 19.032 7.86s13.968-2.792 19.032-7.86L382.678 265c5.076-5.084 7.864-11.872 7.848-19.088.016-7.244-2.772-14.028-7.848-19.108z" />
            </svg>
        </div>

        <ul class="space-y-1 mt-1 pl-11 overflow-hidden transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'max-h-[500px]' : 'max-h-0' }}">
            <li>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white text-[13px] block py-2 hover:translate-x-1 transition-transform relative before:absolute before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-gray-600 before:left-[-12px] before:top-[12px] hover:before:bg-indigo-500">
                    Danh mục
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="text-gray-400 hover:text-white text-[13px] block py-2 hover:translate-x-1 transition-transform relative before:absolute before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-gray-600 before:left-[-12px] before:top-[12px] hover:before:bg-indigo-500">
                    Thương hiệu
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-white {{ request()->routeIs('admin.products.*') ? 'text-white font-medium before:bg-indigo-500' : 'before:bg-gray-600' }} text-[13px] block py-2 hover:translate-x-1 transition-transform relative before:absolute before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:left-[-12px] before:top-[13px] hover:before:bg-indigo-500">
                    Sản phẩm
                </a>
            </li>
        </ul>
    </div>

    {{-- Khách hàng & Đơn hàng --}}
    <ul class="space-y-1.5">
        <h6 class="text-[11px] uppercase font-bold text-gray-500 px-4 mt-8 mb-2 tracking-wider">Kinh doanh</h6>
        <li>
            <a href="javascript:void(0)" class="text-gray-300 hover:text-white hover:bg-gray-800 transition-all text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span>Quản lý khách hàng</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="text-gray-300 hover:text-white hover:bg-gray-800 transition-all text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] mr-3">
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
            <button type="submit" class="w-full text-red-400 hover:text-white hover:bg-red-500/20 font-medium transition-all text-[14px] flex items-center rounded-xl px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[18px] h-[18px] mr-3">
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
  background: #27272a;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #3f3f46;
}
</style>