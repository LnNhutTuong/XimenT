<header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 sticky top-0 z-10">
    <div class="flex items-center gap-4">
        <!-- Hamburger Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-600 focus:outline-none hover:text-black">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-lg font-semibold text-gray-800">Quản trị hệ thống</h1>
    </div>
        
    <div class="flex items-center space-x-4">
          <div class="flex justify-end">
            <a href="{{route('home')}}" class=" btn-base btn-black" ">
                <span class="btn-text"> <-- Trở về trang chủ</span>
                <span class="btn-underline"></span>
            </a>
        </div>
    </div>

</header>
