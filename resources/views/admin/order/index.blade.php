@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
   <div class="flex justify-between items-end mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý đơn hàng</h2>
            <p class="text-md text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $orders->count() }}</span> đơn hàng</p>
        </div>

        <div class="create-order-modal-container">
            <button x-data
                @click="$dispatch('open-modal', 'create-order-modal')"
                class="cursor-pointer block px-4 py-2 rounded-lg text-white text-md font-medium bg-[#09090b] hover:bg-gray-800 transition-all"
            >
                Thêm đơn hàng
            </button>
            @include('admin.order.create')
        </div>
    </div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-5 mb-3">
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng đơn hàng</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $orders->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đơn hàng thành công</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $orders->where('status', 'completed')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Đơn hàng thất bại</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $orders->where('status', 'cancelled')->count() }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        {{-- Search Bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-xl font-semibold text-gray-700">Danh sách đơn hàng</h3>
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-4">
            {{-- Status Filter --}}
            <div class="relative">
                <select 
                    name="status"
                    id="status-filter"
                    class="appearance-none pl-4 pr-10 py-2 text-md border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white"
                    onchange="this.form.submit()"
                >
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="return" {{ request('status') === 'return' ? 'selected' : '' }}>Trả hàng</option>
                </select>
                <!-- <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div> -->
            </div>

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    name="search"
                    id="search-input"
                    type="text"
                    placeholder="Tìm kiếm đơn hàng..."
                    value="{{ request('search') }}"
                    class="pl-9 pr-4 py-2 text-md border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64"
                />
            </div>
            
            @if(request('search') || request('status'))
                <a href="{{ route('admin.order.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                    Xóa lọc
                </a>
            @endif
        </form>
        </div>
            <div class="relative bg-neutral-primary-soft shadow-xs rounded-base border border-default overflow-x-auto w-full">
                <table class="w-full text-left rtl:text-right text-body">
                    <thead>
                        <tr class="bg-gray-50/50  text-lg font-bold border-b border-gray-50">
                            <th class="px-8 py-4 text-center">Mã đơn hàng</th>
                            <th class="px-8 py-4">Khách hàng</th>
                            <th class="px-8 py-4">Tổng tiền</th>
                            <th class="px-8 py-4">Phương thức thanh toán</th>
                            <th class="px-8 py-4">Trạng thái</th>
                            <th class="px-8 py-4">Ngày đặt</th>
                            <th class="px-8 py-4 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="order-tbody">              
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50/30 transition-colors group" data-status="{{ $order->status }}">

                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-4 text-center">
                                    <p class="text-md font-bold text-gray-800 ">{{ $order->order_code }}</p>
                                </div>
                            </td>
                            
                            <td class="px-8 py-4 font-bold">{{ $order->customer->name }}</td>
                            
                            <td class="px-8 py-5">
                                <p class="text-md font-black text-red-600">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </p>
                            </td>

                            <td class="px-8 py-5 font-bold uppercase text-gray-700 text-center">
                                {{ $order->payment_method }}
                            </td>

                            <td class="px-8 py-5">
                                <p class="text-md font-black text-gray-800">
                                    @if( $order->status )
                                        @if($order->status == 'pending')
                                            <span class="text-gray-500">Chờ xác nhận</span>
                                        @elseif($order->status == 'confirmed')
                                            <span class="text-yellow-500">Đã xác nhận</span>
                                        @elseif($order->status == 'shipping')
                                            <span class="text-blue-500">Đang giao hàng</span>
                                        @elseif($order->status == 'completed')
                                            <span class="text-green-500">Hoàn thành</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="text-red-500">Đã hủy</span>
                                        @elseif($order->status == 'return')
                                            <span class="text-red-500">Trả hàng</span>
                                        @endif
                                    @endif
                                </p>
                            </td> 
                              
                            <td class="px-7 py-5">
                                <p class="text-md font-medium text-gray-600">
                                    {{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <button 
                                    x-data @click="$dispatch('open-modal', 'detail-order-modal-{{ $order->id }}')"
                                    class="btn-open-detail cursor-pointer ml-auto block px-4 py-2 rounded-lg text-white text-md font-medium border-none outline-none tracking-wide bg-[#09090a] hover:bg-gray-300 hover:text-black transition-all">
                                    Xem chi tiết
                                </button>
                                @include('admin.order.detail')               
                            </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $orders->links('pagination::tailwind') }}
    </div>
@endsection
