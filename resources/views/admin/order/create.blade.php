<div class="create-order-modal">
<x-my-modal name="create-order-modal" maxWidth="8xl" >
    <x-slot name="title">Thêm đơn hàng</x-slot>
    <x-slot name="body">
        <form action="{{route('admin.orders.store')}}" method="POST" id="createOrderForm">
            @csrf
            <div class="information-customer ">
                <h1 class="text-xl font-bold text-gray-800">Thông tin khách hàng</h1>
                <div class="flex gap-4">
                     <div class="left w-1/2">
                    <div class="mt-2">
                        <label for="choose-type-customers" class="block text-sm font-semibold text-gray-700">Loại khách hàng<span class="text-red-500">*</span></label>
                        <select name="choose-type-customers" id="choose-type-customers" class=" w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="user">Có tài khoản</option>
                            <option value="guest">Khách vãng lai</option>
                        </select>
                    </div>
               

                    <!-- Tên khách hàng -->
                    <div class="mt-2">
                        <label for="user-id" class="block text-sm font-semibold text-gray-700">Tên khách hàng<span class="text-red-500">*</span></label>
                        <!-- chọn khách hàng dành cho khách có tài khoản -->
                        <select name="user-id" id="user-id" class="user hidden w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}"
                                data-phone="{{$customer->phone}}"
                                data-address="{{$customer->address}}"
                                >{{$customer->name}}</option>
                            @endforeach
                        </select>

                        <!-- Nhập vào khi chưa có tài khoản -->
                         <input type="text" name="guest-name" id="guest-name" 
                            placeholder="Ví dụ: Triên Trung Cương"
                            class="non-user w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    </div>
                 

                    <!-- số điện thoại tương ứng với khách chọn -->
                    <div class="mt-2">
                        <label for="customer_phone" class="block text-sm font-semibold text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="customer_phone" required
                            placeholder="Ví dụ: 0123456789"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    </div> 
                </div>

                <div class="right w-1/2 ">
                    <div class="mt-2">
                        <label for="customer_address" class="block text-sm font-semibold text-gray-700">Địa chỉ <span class="text-red-500">*</span></label>
                        <textarea name="address" id="customer_address" required
                            placeholder="Ví dụ: Số 123, Đường ABC..."
                            class="w-full h-[80px] px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"></textarea>
                    </div>
                     <div class="mt-2">
                        <label for="customer_note" class="block text-sm font-semibold text-gray-700">Ghi chú </label>
                        <textarea name="note" id="customer_note" 
                            placeholder="Ví dụ: Ghi chú..."
                            class="w-full h-[35px] px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"></textarea>
                    </div> 
                    <div class="mt-2">
                        <label for="payment_method" class="block text-sm font-semibold text-gray-700">Phương thức thanh toán<span class="text-red-500">*</span></label>
                        <select name="payment_method" id="payment_method" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="vnpay">Thanh toán qua VNPay</option>
                        </select>
                    </div>
                </div>
                </div>
                
                {{-- Hidden inputs for totals --}}
                <input type="hidden" name="total_amount" id="total-price-input" value="0">
                <input type="hidden" name="total_quantity" id="total-quantity-input" value="0">
               

            </div>
            
            <div class="order-detail border-t border-gray-300 mt-3 pt-3">
                <h1 class="text-xl font-bold text-gray-800">Chi tiết đơn hàng</h1>

               <div class="flex gap-4">
                <div class="w-1/2">
                    <div class="flex mb-2 justify-between items-center">
                        <label for="product-list" class="block text-lg font-semibold text-gray-700">Danh sách sản phẩm trong đơn</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <input
                                id="search-input"
                                type="text"
                                placeholder="Tìm kiếm đơn hàng..."
                                class="pl-9 pr-4 py-2 text-md border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64"
                                onkeyup="filterTable()"
                            />
                        </div>
                    </div>   
                    <div class="order-detail-of-user h-[352px] overflow-y-auto relative bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                        <table class="w-full text-left rtl:text-right text-body border-collapse">
                            <thead class="sticky top-0 bg-gray-50 z-10 text-lg font-bold border-b border-gray-50">
                                <tr>
                                    <th class="px-8 py-4 text-center">Tên sản phẩm</th>
                                    <th class="px-8 py-4 text-center">Số lượng</th>                                   
                                    <th class="px-8 py-4 text-center">Thành tiền</th>
                                    <th class="px-8 py-4 text-center">Hành động</th>
                                </tr>
                            </thead>
                            
                            <tbody id="order-items-body">                          
                            </tbody>
                        </table>                        
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-200 flex justify-between items-center">
                            <div class="text-gray-700">
                                <span class="font-medium">Tổng số lượng:</span>
                                <span id="total-quantity-display" class="font-bold text-black-600 ml-1">0</span>
                            </div>
                            <div class="text-gray-900">
                                <span class="text-lg font-medium">Tổng thanh toán:</span>
                                <span id="total-price-display" class="text-xl font-bold text-red-600 ml-2">0 VNĐ</span>
                            </div>
                        </div>
                </div>
                <div class="w-1/2">

                    <div class="flex mb-2 justify-between items-center">
                        <label for="product-list" class="block text-lg font-semibold text-gray-700">Danh sách sản phẩm trong kho</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <input
                                id="search-input"
                                type="text"
                                placeholder="Tìm kiếm đơn hàng..."
                                class="pl-9 pr-4 py-2 text-md border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64"
                                onkeyup="filterTable()"
                            />
                        </div>
                    </div>
                    
                    <div class="product-list h-[352px] overflow-y-auto relative bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                            <table class="w-full text-left rtl:text-right text-body border-collapse">
                                <thead class="sticky top-0 bg-gray-50 z-10 text-lg font-bold border-b border-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-center">Tên sản phẩm</th>
                                        <th class="px-6 py-4 text-center">Size</th>
                                        <th class="px-6 py-4 text-center">Số lượng kho</th>
                                        <th class="px-6 py-4 text-center">Giá tiền</th>                                   
                                        <th class="px-6 py-4 text-center">Hành động</th>

                                    </tr>
                                </thead>
                            
                                <tbody>
                                @foreach ($products as $product)
                                        @foreach ($product->variants as $variant)
                                        @if($variant->stock_quantity > 0)
                                        <tr class="border-b">
                                            <td class="px-8 py-2 text-center">{{ $product->name }}</td>
                                            <td class="px-8 py-2 text-center font-bold text-blue-600">{{ $variant->size->name }}</td>
                                            <td class="px-8 py-2 text-center">
                                                <input type="number" 
                                                    class="input-quantity-buy w-16 border rounded text-center" 
                                                    value="1" 
                                                    min="1" 
                                                    max="{{ $variant->stock_quantity }}">
                                                <span class="text-xs text-gray-400 block">Kho: {{ $variant->stock_quantity }}</span>
                                            </td>
                                            <td class="px-8 py-2 text-center">
                                                @if($variant->discount_price)
                                                    <span class="text-red-500 line-through">{{ number_format($variant->price) }} VNĐ</span>
                                                    <br>
                                                    <span>{{ number_format($variant->discount_price) }} VNĐ</span>
                                                @else
                                                    <span>{{ number_format($variant->price) }} VNĐ</span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-2 text-center ">
                                                <button class="btn-add-order-detail"
                                                        type="button"
                                                        data-product-id="{{ $product->id }}"
                                                        data-variant-id="{{ $variant->id }}"
                                                        data-product-name="{{ $product->name }}"
                                                        data-size="{{ $variant->size->name }}"
                                                        data-price="{{ $variant->discount_price ?? $variant->price }}"
                                                        data-quantity="{{ $variant->stock_quantity }}"
                                                >
                                                    <svg class="size-10 hover:cursor-pointer hover:text-green-500 transform hover:-translate-y-0.5 transition-all" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                @endforeach
                                </tbody>
                        </table>
                    </div> 
                </div>
               </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="footer">
        <button 
            @click="show = false" 
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-all">
            Hủy
        </button>
        <button 
            type="submit" 
            form="createOrderForm" 
            class="px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
            Thêm sản phẩm
        </button>
    </x-slot>
</x-my-modal>
</div>

@push('scripts')
    @vite('resources/js/admin/order/order-create.js');
@endpush