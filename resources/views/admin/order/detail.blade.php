<div class="detail-order-modal-container">
    <x-my-modal name="detail-order-modal-{{ $order->id }}" maxWidth="8xl" >
    <x-slot name="title">Chi tiết đơn hàng</x-slot>
    <x-slot name="body">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="detail-order-form-{{ $order->id }}">
            @csrf
            @method('PUT')
            <div class="information-customer ">
                <div class="flex justify-between">
                    <h1 class="text-xl font-bold text-gray-800">Thông tin khách hàng</h1>   
                    <div>
                        <label for="status-order" class="block text-sm font-semibold text-gray-700">Trạng thái đơn hàng</label>
                        <select name="status" id="status-order" 
                                class="status-order bg-gray-100 w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                                readonly>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="return" {{ $order->status == 'return' ? 'selected' : '' }}>Trả hàng</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-4">
                     <div class="left w-1/2">
                    <div class="mt-2">
                        <label for="choose-type-customers" class="block text-sm font-semibold text-gray-700">Loại khách hàng<span class="text-red-500">*</span></label>
                        <select name="choose-type-customers" id="choose-type-customers-{{ $order->id }}" class="bg-gray-100 choose-type-customers w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                                disabled>
                            <option value="user" {{ $order->user_id ? 'selected' : '' }}>Có tài khoản</option>
                            <option value="guest" {{ !$order->user_id ? 'selected' : '' }}>Khách vãng lai</option>
                        </select>
                    </div>
               

                    <!-- Tên khách hàng -->
                    <div class="mt-2">
                        <label for="user-id" class="block text-sm font-semibold text-gray-700">Tên khách hàng<span class="text-red-500">*</span></label>
                        <!-- chọn khách hàng dành cho khách có tài khoản -->
                        <select name="user-id" id="user-id-{{ $order->id }}" 
                                class="bg-gray-100 user {{ $order->user_id ? '' : 'hidden' }} w-full px-4 py-3 text-sm border 
                                border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 
                                transition-all shadow-sm" readonly>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}"
                                 {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach                            
                        </select>

                        <!-- Nhập vào khi chưa có tài khoản -->
                         <input type="text" name="guest-name" id="guest-name-{{ $order->id }}" 
                            placeholder="Ví dụ: Triên Trung Cương"
                            class="bg-gray-100 guest-name non-user {{ !$order->user_id ? '' : 'hidden' }} w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                            value="{{ !$order->user_id ? $order->customer->name : '' }}" readonly>

                    </div>
                 

                    <!-- số điện thoại tương ứng với khách chọn -->
                    <div class="mt-2">
                        <label for="customer_phone" class="block text-sm font-semibold text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="customer_phone" required
                            placeholder="Ví dụ: 0123456789"
                            class="bg-gray-100 customer_phone w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                            value="{{ $order->phone }}"
                            readonly>
                    </div> 
                </div>

                <div class="right w-1/2 ">
                    <div class="mt-2">
                        <label for="customer_address" class="block text-sm font-semibold text-gray-700">Địa chỉ <span class="text-red-500">*</span></label>
                        <textarea name="address" id="customer_address" required
                            placeholder="Ví dụ: Số 123, Đường ABC..."
                            class="bg-gray-100 customer_address w-full h-[80px] px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                            readonly>{{ $order->address }}</textarea>
                    </div>
                     <div class="mt-2">
                        <label for="customer_note" class="block text-sm font-semibold text-gray-700">Ghi chú </label>
                        <textarea name="note" id="customer_note" 
                            placeholder="Ví dụ: Ghi chú..."
                            class="bg-gray-100 w-full h-[80px] px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                            readonly>{{ $order->note }}</textarea>
                    </div> 
                </div>
                </div>
               

            </div>
            
            <div class="order-detail border-t border-gray-300 mt-3 pt-3">
                <h1 class="text-xl font-bold text-gray-800">Chi tiết đơn hàng</h1>

               <div class="flex gap-4">
                <div class="w-full" id="order-detail-of-user">
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
                            
                            <tbody class="order-items-body">
                                @foreach($order->details as $orderItem)
                                    <tr data-variant-id="{{ $orderItem->variant_id }}"
                                        data-price="{{ $orderItem->price }}" 
                                        data-quantity="{{ $orderItem->variant->stock_quantity }}">
                                        <td class="px-8 py-2 text-center">
                                            <div class="font-semibold">{{ $orderItem->variant->product->name }}</div>
                                            <div class="text-xs text-blue-500">Size: {{ $orderItem->variant->size->name }}</div>
                                        </td>                                        
                                        <td class="px-8 py-4 text-center">
                                             <input type="number" 
                                                    value="{{ $orderItem->quantity }}" 
                                                    class="w-16 border rounded text-center input-item-quantity" 
                                                    readonly> 
                                        </td>
                                        <td class="px-8 py-4 text-center row-total">{{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }} VNĐ</td>
                                        <td class="px-8 py-4 text-center">
                                          <button type="button" class="btn-remove-order-detail hover:text-red-700 opacity-20" disabled>
                                            <svg class="size-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                          </button>
                                        </td>
                                    </tr>
                                @endforeach                    
                            </tbody>
                        </table>                        
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-200 flex justify-between items-center">
                            <div class="text-gray-700">
                                <span class="font-medium">Tổng số lượng:</span>
                                <span class="total-quantity-display font-bold text-black-600 ml-1"> {{ $order->details->sum('quantity') }}</span>
                            </div>
                            <div class="text-gray-900">
                                <span class="text-lg font-medium">Tổng thanh toán:</span>
                                <span class="total-price-display text-xl font-bold text-red-600 ml-2">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                </div>
               </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="footer">
        <div class="pt-6 border-t border-gray-100 flex gap-4">
                    <button type="submit" form="detail-order-form-{{ $order->id }}" class="btn-accept-edit hidden px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
                        Cập nhật
                    </button>
                    <button type="button" class="btn-cancel-edit hidden px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-gray-100 hover:bg-gray-200 transition-all transform hover:-translate-y-0.5">
                        Hủy bỏ
                    </button>
                    <button type="button" class="btn-edit-order px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
                        Chỉnh sửa
                    </button>
                    <button type="submit" form="form-delete-{{ $order->id}}" class="btn-delete-order px-8 py-3 rounded-xl text-red-600 text-sm font-bold bg-red-50 border border-red-100 hover:bg-red-600 hover:text-white transition-all transform hover:-translate-y-0.5">
                        Xóa
                    </button>
            </div>      
    </x-slot>
</x-my-modal>
</div>
@push('scripts')
    @vite('resources/js/admin/order/order-detail.js');
@endpush