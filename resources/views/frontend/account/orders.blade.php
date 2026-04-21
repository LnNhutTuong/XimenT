@extends('frontend.layouts.app')

@section('title', 'Theo dõi đơn hàng - XimenT')

@section('content')
<div class="product-container min-h-screen mx-auto container px-4 lg:px-0">
    <header class="flex justify-between border-b border-gray-100 pb-4">
        <h1 class="text-3xl md:text-4xl font-light mb-4 mt-4 text-slate-800">Theo dõi đơn hàng</h1>
    </header>

    <div class="mt-8 list-orders h-full max-w-5xl">
        @if($orders->isEmpty())
            <div class="bg-white rounded-md shadow-sm border p-12 text-center">
                <p class="text-xl text-gray-400 font-light italic uppercase tracking-widest">Bạn chưa có đơn hàng nào.</p>
                <a href="{{ route('product') }}" class="mt-8 inline-block border border-gray-700 px-10 py-3 rounded-md hover:bg-black hover:text-white transition-all text-sm font-bold uppercase tracking-[0.2em]">
                    Mua sắm ngay
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach($orders as $order)
                <div class="bg-white flex flex-col rounded-md shadow-sm relative border hover:border-gray-300 transition-all duration-300">
                    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center bg-gray-50/30">
                        <div class="flex items-center gap-4">
                            <h3 class="text-lg font-bold text-slate-800">Mã đơn: <span class="text-indigo-600">#{{ $order->order_code }}</span></h3>
                            <span class="text-gray-300">|</span>
                            <span class="text-sm text-gray-400 italic">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div>
                            @php
                                $statusClasses = [
                                    'pending' => 'text-amber-600',
                                    'confirmed' => 'text-blue-600',
                                    'shipping' => 'text-indigo-600',
                                    'completed' => 'text-green-600',
                                    'cancelled' => 'text-red-600',
                                    'return' => 'text-red-400',
                                ];
                                $statusLabels = [
                                    'pending' => 'Đang chờ duyệt',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao hàng',
                                    'completed' => 'Đã hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                    'return' => 'Trả hàng',
                                ];
                                $currentStatus = $order->status ?? 'pending';
                            @endphp
                            <span class="text-xs font-bold uppercase tracking-[0.2em] {{ $statusClasses[$currentStatus] }}">
                                {{ $statusLabels[$currentStatus] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                            <div class="md:col-span-6 space-y-2">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Thông tin nhận hàng</h4>
                                <p class="text-lg font-bold text-slate-800 uppercase tracking-tight">{{ $order->customer->name ?? $order->user->name }}</p>
                                <div class="flex flex-col gap-1 text-sm text-slate-600">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"/></svg>
                                        {{ $order->phone }}
                                    </span>
                                    <span class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                                        {{ $order->address }}
                                    </span>
                                </div>
                            </div>

                            <div class="md:col-span-3 space-y-1">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Thanh toán</h4>
                                <p class="text-2xl font-bold text-slate-800">{{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">{{ $order->payment_method ?? 'COD' }}</p>
                            </div>

                            <div class="md:col-span-3 flex justify-end">
                                <button x-data 
                                    @click="$dispatch('open-modal', 'detail-order-modal-{{ $order->id }}')"
                                class="w-full md:w-auto border border-gray-700 px-8 py-3 rounded-md text-xs font-bold uppercase tracking-[0.2em] hover:bg-black hover:text-white transition-all">
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @include('frontend.account.detail')
                @endforeach

                <div class="mt-12 flex justify-center">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
