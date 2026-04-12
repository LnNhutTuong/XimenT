<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Chào mừng, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mb-8 text-lg">Đây là trang cá nhân của bạn tại XimenT. Tại đây bạn có thể quản lý đơn hàng và thông tin cá nhân.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Thẻ thông tin nhanh -->
                        <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                            <h3 class="font-bold text-blue-800 uppercase text-xs tracking-wider">Vai trò của bạn</h3>
                            <p class="text-2xl font-bold text-blue-900 mt-1 capitalize">{{ auth()->user()->role }}</p>
                        </div>

                        <div class="p-6 bg-green-50 rounded-xl border border-green-100">
                            <h3 class="font-bold text-green-800 uppercase text-xs tracking-wider">Trạng thái tài khoản</h3>
                            <p class="text-2xl font-bold text-green-900 mt-1">Đã xác minh</p>
                        </div>

                        <div class="p-6 bg-purple-50 rounded-xl border border-purple-100">
                            <h3 class="font-bold text-purple-800 uppercase text-xs tracking-wider">Đơn hàng của tôi</h3>
                            <p class="text-2xl font-bold text-purple-900 mt-1">0 đơn hàng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
