@extends('frontend.layouts.app')

@section('content')
<!-- 1. Hero Carousel (Phong cách sang trọng) -->
<div class="swiper heroSwiper w-full h-[60vh] md:h-[80vh]">
    <div class="swiper-wrapper">
        <div class="swiper-slide relative overflow-hidden">
            <img src="{{ asset('storage/home/index/YohjiYamamoto.jpg') }}" class="w-full h-full object-cover transition-transform duration-[10s] hover:scale-110">
            <div class="absolute inset-0 bg-black/20 flex flex-col items-center justify-center text-white p-4">
                <h2 class="text-4xl md:text-4xl font-extralight tracking-widest mb-4 animate-fadeIn">“Black is modest and arrogant at the same time.”</h2>
                <p class="text-lg md:text-xl font-light tracking-[0.3em]  opacity-80 mb-8 font-serif">- Yohji Yamamoto -</p>
                <a href="{{ route('product') }}" class="border border-white px-10 py-3 text-sm uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-500">
                    Khám phá ngay
                </a>
            </div>
        </div>
        <!-- Thêm các banner khác nếu cần -->
    </div>
    <div class="swiper-pagination"></div>
</div>

<!-- 2. Section Sản phẩm mới (Sử dụng style Product Card của bạn) -->
<div class="container mx-auto py-20 px-4">
    <div class="flex items-center justify-between mb-12 border-b border-gray-100 pb-6">
        <h2 class="text-3xl md:text-4xl font-light tracking-tight text-slate-900">Sản phẩm mới nhất</h2>
        <a href="{{ route('product') }}" class="text-sm uppercase tracking-widest border-b border-black pb-1 hover:opacity-60 transition-all">
            Xem tất cả
        </a>
    </div>
    
    <div class="relative group/swiper px-10 md:px-12"> 
        <div class="swiper productSwiper !pb-8">
            <div class="swiper-wrapper">
            @foreach($list_products as $product)
                <div class="swiper-slide list-product">
                    <div class="bg-white flex flex-col rounded-md shadow-sm relative border hover:border-gray-300 transition-all duration-300 group">
                        <a href="{{ route('product.detail', $product->slug) }}" class="rounded-t-md block overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('storage/empty/empty-image-product.png') }}"                               
                                alt="{{ $product->name }}"
                                class="w-full aspect-[4/5] object-cover object-top group-hover:scale-105 transition-transform duration-500" />
                            
                            <div class="p-4">
                                <div class="group-hover:hidden transition-all duration-300">
                                    <h3 class="text-base font-bold text-slate-800 line-clamp-1">
                                        {{ $product->name }}
                                    </h3>
                                    <div class="flex items-center gap-2 mt-2">
                                        @php $minVariant = $product->variants->sortBy('price')->first(); @endphp
                                        @if($product->variants->where('discount_price', '>', 0)->first())
                                            <p class="text-base font-bold text-red-600">
                                                {{ number_format($product->variants->min('discount_price'), 0, ',', '.') }}đ
                                            </p>
                                            <p class="text-xs text-gray-400 line-through">
                                                {{ number_format($product->variants->min('price'), 0, ',', '.') }}đ
                                            </p>
                                        @else
                                            <p class="text-base font-bold text-slate-700">
                                                {{ number_format($product->variants->min('price'), 0, ',', '.') }}đ
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                   
                                <div class="hidden group-hover:block transition-all duration-300 mt-0">
                                    <h3 class="text-sm font-bold text-slate-800">Còn hàng:</h3>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach ($product->variants as $variant)
                                            <span class="text-xs px-2 py-1 bg-gray-100 rounded text-slate-600">
                                                {{ $variant->size->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </a>    
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="swiper-button-next product-next !text-black after:!text-2xl transition-all opacity-0 group-hover/swiper:opacity-100"></div>
    <div class="swiper-button-prev product-prev !text-black after:!text-2xl transition-all opacity-0 group-hover/swiper:opacity-100"></div>

</div>

@push('scripts')
<script>
new Swiper(".productSwiper", {
    slidesPerView: 2,
    spaceBetween: 16,
    centerInsufficientSlides: true,
    loop: true,
    autoplay: {
        delay: 3000, 
        disableOnInteraction: false, // Tiếp tục chạy sau khi người dùng chạm vào
    },
    navigation: {
        nextEl: ".product-next",
        prevEl: ".product-prev",
    },
    breakpoints: {
        640: { slidesPerView: 3, spaceBetween: 24 },
        1024: { slidesPerView: 4, spaceBetween: 32 },
    }
});

</script>
<style>
    .product-next {
        right: 0 !important;
        width: 40px !important;
    }
    .product-prev {
        left: 0 !important;
        width: 40px !important;
    }

    .product-next::after, .product-prev::after {
        font-size: 24px !important;
        font-weight: bold !important;
    }

    .product-next:hover, .product-prev:hover {
        color: #000 !important;
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .relative.group\/swiper {
            padding-left: 0;
            padding-right: 0;
        }
        .product-next, .product-prev {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
