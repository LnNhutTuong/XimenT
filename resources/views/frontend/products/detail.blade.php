@extends('frontend.layouts.app')

@section('title', ($product->name ?? 'Chi tiết sản phẩm') . ' - XimenT')

@section('content')
<div class="product-detail-container min-h-screen mb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-0 py-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-5 uppercase tracking-widest" aria-label="Breadcrumb">
            <h1 class="text-3xl md:text-4xl font-light">{{ $product->name }}</h1>
        </nav>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-16 lg:items-start">
            <div class="lg:col-span-7 flex flex-col-reverse lg:flex-row gap-6">
                @if(count($productImages) > 0)
                <div class="hidden lg:block w-24 flex-shrink-0">
                    <div class="flex flex-col gap-4">
                        <div class="aspect-h-4 aspect-w-3 relative cursor-pointer border border-transparent hover:border-black transition-all duration-300 rounded overflow-hidden" 
                        onclick="changeImage('{{ asset('storage/' . $product->image) }}')">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Main" class="w-full h-full object-cover">
                        </div>
                        @foreach($productImages as $image)
                        <div class="aspect-h-4 aspect-w-3 relative cursor-pointer border border-transparent hover:border-black transition-all duration-300 rounded overflow-hidden" 
                        onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex-1">
                    <div class="aspect-h-5 aspect-w-4 overflow-hidden bg-white border border-gray-100 relative group rounded-sm">
                        <img id="main-product-image" src="{{ $product->image ? asset('storage/' . $product->image) : asset('storage/empty/empty-image-product.png') }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover transition-all duration-700 group-hover:scale-105">
                    </div>
                    
                    <div class="lg:hidden mt-6 flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                         <div class="w-24 aspect-h-4 aspect-w-3 flex-shrink-0 cursor-pointer border border-black rounded overflow-hidden" onclick="changeImage('{{ asset('storage/' . $product->image) }}')">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        </div>
                        @foreach($productImages as $image)
                        <div class="w-24 aspect-h-4 aspect-w-3 flex-shrink-0 cursor-pointer rounded overflow-hidden" onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 mt-10 lg:mt-0 sticky top-10">
                <div class="flex flex-col">
                    <div class="mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                            {{ $product->brand->name ?? 'Premium Collection' }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-light text-slate-800 leading-tight tracking-tight uppercase mb-6">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-baseline mb-10" id="price-wrapper">
                        @php
                            $hasDiscountGlobal = $product->variants->where('discount_price', '>', 0)->count() > 0;
                            $displayPrice = $hasDiscountGlobal 
                                ? $product->variants->where('discount_price', '>', 0)->min('discount_price') 
                                : $product->variants->min('price');
                        @endphp

                        <span id="display-actual-price" class="product-price-display text-3xl font-bold {{ $hasDiscountGlobal ? 'text-red-600' : 'text-slate-700' }}">
                            {{ number_format($displayPrice, 0, ',', '.') }}đ
                        </span>
                        
                        <span id="display-old-price" class="ml-3 text-xs text-gray-400 line-through {{ $hasDiscountGlobal ? '' : 'hidden' }}">
                            {{ number_format($product->variants->min('price'), 0, ',', '.') }}đ
                        </span>
                    </div>

                    <div class="mb-10">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-6">
                            <h3 class="text-sm text-slate-800 font-bold uppercase tracking-widest">Kích thước</h3>
                        </div>

                        <div class="grid grid-cols-4 gap-4 mt-2">
                            @foreach($productVariants as $variant)
                            <label class="size-label relative border border-slate-200 rounded-sm py-4 px-2 flex items-center justify-center text-sm font-medium uppercase transition-all duration-300 cursor-pointer 
                                {{ $variant->stock_quantity <= 0 ? 'bg-slate-50 text-slate-300 cursor-not-allowed border-dashed' : 'hover:border-slate-800 hover:shadow-sm bg-white text-slate-800' }}
                                select-none">
                                <input type="radio" name="size-choice" value="{{ $variant->id }}" class="sr-only p-size-input" 
                                    data-price="{{ number_format($variant->price, 0, ',', '.') }}đ"
                                    data-discount-price="{{ $variant->discount_price > 0 ? number_format($variant->discount_price, 0, ',', '.') . 'đ' : '' }}"
                                    data-stock="{{ $variant->stock_quantity }}"
                                    {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                <span class="relative z-10">{{ $variant->size->name }}</span>
                                <div class="absolute inset-0 border-2 border-transparent rounded-sm transition-colors duration-300 select-indicator"></div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                        
                    <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form" class="flex items-stretch gap-4 h-14">
                        @csrf
                        <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                        <div class="flex border border-slate-200 rounded-sm w-36 overflow-hidden bg-white">
                            <button type="button" class="flex-1 flex items-center justify-center hover:bg-slate-50 transition" onclick="updateQty(-1)">
                                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M20 12H4" stroke-width="2"/></svg>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-14 text-center bg-transparent border-none focus:ring-0 text-base font-bold text-slate-800">
                            <button type="button" class="flex-1 flex items-center justify-center hover:bg-slate-50 transition" onclick="updateQty(1)">
                                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4v16m8-8H4" stroke-width="2"/></svg>
                            </button>
                        </div>
                        <button type="submit" class="flex-1 bg-slate-800 text-white text-sm font-bold uppercase tracking-[0.2em] rounded-sm hover:bg-black transform active:scale-[0.98] transition-all duration-300 shadow-xl shadow-slate-200">
                            Thêm vào giỏ hàng
                        </button>
                    </form>


                    <div class="space-y-8">
                        <div class="border-t border-slate-100 pt-8">
                            <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-slate-800">Mô tả sản phẩm</h3>
                            <div class="text-[15px] text-slate-600 leading-relaxed font-normal prose prose-slate max-w-none prose-p:my-2">
                                {!! $product->description !!}
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-8">
                            <details class="group">
                                <summary class="flex justify-between items-center cursor-pointer list-none">
                                    <span class="text-sm font-bold uppercase tracking-widest text-slate-800">Chính sách vận chuyển</span>
                                    <span class="transition-transform duration-300 group-open:rotate-180">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" stroke-width="2"/></svg>
                                    </span>
                                </summary>
                                <div class="mt-6 text-[14px] text-slate-500 font-normal leading-relaxed">
                                    Đơn hàng sẽ được xử lý và giao từ 2-5 ngày làm việc. 
                                    Miễn phí vận chuyển cho đơn hàng từ 2.000.000đ. 
                                    Các sản phẩm đổi trả phải còn nguyên vẹn, đúng với sản phẩm đã được giao.
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/frontend/products/detail/common.js')
@endpush