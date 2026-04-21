@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng - XimenT')

@section('content')
<div class="product-container min-h-screen mx-auto container px-4 lg:px-0">

    {{-- Header --}}
    <header class="flex justify-between ml-4">
        <h1 class="text-3xl md:text-4xl font-light mb-4 mt-4">Giỏ hàng
            <span class="text-xl text-gray-400 font-normal ml-2">({{ $cartItems->count() }} sản phẩm)</span>
        </h1>
    </header>

    <div class="mt-8 ml-4">
        @if($cartItems->isEmpty())
            {{-- Giỏ hàng trống --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg class="w-16 h-16 text-gray-200 mb-6" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <p class="text-gray-400 text-base mb-6">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('product') }}" class="bg-slate-800 text-white text-sm font-bold px-8 py-3 hover:bg-black transition-all duration-300">
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">

                {{-- Danh sách sản phẩm --}}
                <div class="lg:col-span-8">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-300 text-base text-slate-800">
                                <th class="text-left pb-3 font-bold">Sản phẩm</th>
                                <th class="text-center pb-3 font-bold">Giá</th>
                                <th class="text-center pb-3 font-bold">Số lượng</th>
                                <th class="text-right pb-3 font-bold">Tổng</th>
                                <th class="pb-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-all duration-300">
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('product.detail', $item->variant->product->slug) }}"
                                           class="block w-16 h-20 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 group">
                                            <img src="{{ $item->variant->product->image ? asset('storage/' . $item->variant->product->image) : asset('storage/empty/empty-image-product.png') }}"
                                                 alt="{{ $item->variant->product->name }}"
                                                 class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                                        </a>
                                        <div>
                                            <a href="{{ route('product.detail', $item->variant->product->slug) }}"
                                               class="text-base font-bold text-slate-800 line-clamp-1 hover:underline">
                                                {{ $item->variant->product->name }}
                                            </a>
                                            <p class="text-sm text-gray-400 mt-1">
                                                Size: {{ $item->variant->size->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 text-center">
                                    @if($item->variant->discount_price > 0)
                                        <span class="text-base font-bold text-red-600">{{ number_format($item->variant->discount_price, 0, ',', '.') }}đ</span>
                                        <br>
                                        <span class="text-xs text-gray-400 line-through">{{ number_format($item->variant->price, 0, ',', '.') }}đ</span>
                                    @else
                                        <span class="text-base font-bold text-slate-700">{{ number_format($item->variant->price, 0, ',', '.') }}đ</span>
                                    @endif
                                </td>

                                <td class="py-4 text-center">
                                    <span class="inline-block border border-gray-700 rounded-md px-4 py-1 text-base font-bold text-slate-800 min-w-[48px] text-center">
                                        {{ $item->quantity }}
                                    </span>
                                </td>

                                <td class="py-4 text-right text-base font-bold text-slate-800">
                                    {{ number_format(($item->variant->discount_price ?? $item->variant->price) * $item->quantity, 0, ',', '.') }}đ
                                </td>

                                <td class="py-4 text-right pl-4">
                                    <button type="submit" form="form-delete-{{ $item->id}}" class="text-gray-300 hover:text-red-500 transition-all duration-300" title="Xóa sản phẩm">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <form id="form-delete-{{ $item->id}}" action="{{ route('cart.delete', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        <a href="{{ route('product') }}" class="text-base text-slate-800 hover:underline flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-4 mt-10 lg:mt-0">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="border border-gray-300 rounded-md p-6 sticky top-10">
                            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-4 border-b border-gray-200">
                                Thông tin giao hàng
                            </h2>

                            <div class="space-y-4 mb-6">
                                <div>
                                    <label for="shipping_name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Họ và tên</label>
                                    <input type="text" name="name" id="shipping_name" required 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-slate-800"
                                        value="{{ old('name', $customer->name ?? Auth::user()->name) }}"
                                        placeholder="Ví dụ: Nguyễn Văn A">
                                </div>
                                <div>
                                    <label for="shipping_phone" class="block text-xs font-bold text-gray-500 uppercase mb-1">Số điện thoại</label>
                                    <input type="text" name="phone" id="shipping_phone" required 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-slate-800"
                                        value="{{ old('phone', $customer->phone ?? '') }}"
                                        placeholder="Ví dụ: 0912345678">
                                </div>
                                <div>
                                    <label for="shipping_address" class="block text-xs font-bold text-gray-500 uppercase mb-1">Địa chỉ nhận hàng</label>
                                    <textarea name="address" id="shipping_address" required rows="2"
                                        class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-slate-800"
                                        placeholder="Số nhà, tên đường, phường/xã...">{{ old('address', $customer->address ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label for="shipping_note" class="block text-xs font-bold text-gray-500 uppercase mb-1">Ghi chú (Tùy chọn)</label>
                                    <textarea name="note" id="shipping_note" rows="1"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-slate-800"
                                        placeholder="Ghi chú về đơn hàng, ví dụ: Giao giờ hành chính"></textarea>
                                </div>
                            </div>

                            <h2 class="text-xl font-bold text-slate-800 mb-4 pb-4 border-b border-gray-200">
                                Đơn hàng
                            </h2>

                            <div class="space-y-3">
                                <div class="flex justify-between text-base text-gray-600">
                                    <span>Tạm tính</span>
                                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="flex justify-between text-base text-gray-600">
                                    <span>Phí vận chuyển</span>
                                    <span class="{{ $total >= 2000000 ? 'text-green-600 font-bold' : 'text-slate-800 font-bold' }}">
                                        {{ $total >= 2000000 ? 'Miễn phí' : number_format(30000, 0, ',', '.') . 'đ' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between text-slate-800">
                                <span class="text-base font-bold">Tổng cộng</span>
                                <span class="text-xl font-bold">
                                    {{ number_format($total >= 2000000 ? $total : $total + 30000, 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200 flex flex-col gap-2">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Phương thức thanh toán</p>
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label for="cod" class="text-sm">Thanh toán khi nhận hàng</label>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="radio" name="payment_method" id="vnpay" value="vnpay" >
                                    <label for="vnpay" class="text-sm">Thanh toán qua VNPAY / VietQR</label>
                                </div>

                                <div id="payment-vnpay-instruction" class="hidden mt-2 p-3 bg-gray-50 rounded border border-gray-200">
                                    <p class="text-[11px] text-gray-500 mb-2">Sau khi nhấn "Đặt hàng", vui lòng chuyển khoản theo QR hoặc hướng dẫn dưới đây:</p>
                                    <img 
                                        id="vnpay-img"
                                        class="w-full mb-2"
                                        src="https://img.vietqr.io/image/VCB-1041120402-compact2.png?accountName=LAM%20NGUYEN%20NHAT%20TUONG&amount={{ $total >= 2000000 ? $total : $total + 30000 }}&addInfo='XimenT'">
                                    <p class="text-[11px] text-red-500 italic">
                                        * Nội dung CK: Mã đơn hàng + Số điện thoại <br>
                                    Sau khi THANH TOÁN THÀNH CÔNG thực hiện gửi ảnh kèm nội dung CK qua Zalo: 0333814020 để được xác nhận đơn hàng.
                                    </p>
                                </div>
                            </div>

                            <button type="submit" class="mt-6 w-full bg-slate-800 text-white text-sm font-bold py-3 hover:bg-black transition-all duration-300 rounded-md">
                                Hoàn tất đặt hàng
                            </button>

                            @if($total < 2000000)
                            <p class="mt-4 text-sm text-gray-400 text-center">
                                Mua thêm <span class="font-bold text-slate-600">{{ number_format(2000000 - $total, 0, ',', '.') }}đ</span> để được miễn phí vận chuyển
                            </p>
                            @else
                            <p class="mt-4 text-sm text-green-600 font-bold text-center">Bạn được miễn phí vận chuyển!</p>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        @endif
    </div>
</div>

@if(session('order_success'))
    <div id="order-success-trigger" 
         data-code="{{ session('order_code') }}" 
         data-phone="{{ session('phone') }}"
         data-product-url="{{ route('product') }}">
    </div>
@endif
@endsection

@push('scripts')
    @vite('resources/js/frontend/cart/common.js')
@endpush