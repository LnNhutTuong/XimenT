<x-my-modal name="detail-customer-modal-{{ $customer->id }}" maxWidth="2xl">
    <x-slot name="title" class="flex flex-col gap-4">
        <div class="text-xl font-semibold text-gray-700">
            Chi tiết khách hàng
        </div>
        <div>
            @php
                if($customer->user_id){
                    echo "<span class='text-sm text-green-500'>
                        Có tài khoản: 
                            " . ($customer->user->status == 1 ? "<span class='text-md text-green-500 uppercase tracking-wider font-semibold'>Hoạt động</span>" : "<span class='text-md text-red-500 uppercase tracking-wider font-semibold'>Bị khóa</span>") . "
                    </span>";
                }else{
                    echo "<span class='text-sm text-red-500'>Khách vãng lai</span>";
                }
            @endphp 
        </div>
  
    </x-slot>
    <x-slot name="body">
               <!-- name -->
                <div class="flex gap-4">
                    <div class="flex-1">
                        <div class="mt-2">
                            <label for="product_name_{{$customer->id}}" class="block text-sm font-semibold text-gray-700">Tên Khách hàng <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="product_name_{{$customer->id}}" required disabled
                                class="product-name-input w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                value="{{ $customer->name }}">
                        </div>
                        <div class="mt-2">
                            <label for="email_{{$customer->id}}" class="block text-sm font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="text" name="email" id="email_{{$customer->id}}" required disabled
                                class="product-name-input w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                value="{{ $customer->email }}">
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="mt-2">
                            <label for="phone_{{$customer->id}}" class="block text-sm font-semibold text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone_{{$customer->id}}" required disabled
                                class="product-name-input w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                value="{{ $customer->phone }}">
                        </div>
                        <div class="mt-2">
                            <label for="address_{{$customer->id}}" class="block text-sm font-semibold text-gray-700">Địa chỉ <span class="text-red-500">*</span></label>
                            <input type="text" name="address" id="address_{{$customer->id}}" required disabled
                                class="product-name-input w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                value="{{ $customer->address }}">   
                        </div>
                    </div>
                </div>

                
                <div class="mt-4 flex justify-center gap-4 mx-10">
                    <div class="flex-1 text-md font-semibold text-gray-700 p-4 bg-green-600 !text-white rounded-lg">Đơn thành công: {{ $customer->orders()->where('status', 1)->count() }}</div>
                    <div class="flex-1 text-md font-semibold text-gray-700 p-4 bg-red-600 !text-white rounded-lg">Đơn thất bại: {{ $customer->orders()->where('status', 0)->count() }}</div>
                </div>
    </x-slot>
    <x-slot name="footer">
        <button @click="show = false" class="btn-close-detail cursor-pointer block px-4 py-2 rounded-lg text-white text-md font-medium bg-[#09090b] hover:bg-gray-800 transition-all">Đóng</button>
    </x-slot>
</x-my-modal>