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
            XimenT  
        </div>

        <div class="flex justify-end">
  
             <div class="flex items-center space-x-3">

                <x-button class="btn-base">
                    <span class="btn-text btn-black">Đăng nhập</span>
                    <span class="btn-underline btn-black"></span>
                </x-button>

               
                <x-button class="btn-base">
                    <span class="btn-text btn-black">Đăng ký</span>
                    <span class="btn-underline btn-black"></span>
                </x-button>

            </div>
        </div>
    </nav>
</header>
