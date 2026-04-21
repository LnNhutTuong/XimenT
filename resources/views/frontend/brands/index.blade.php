@extends('frontend.layouts.app')
@section('title', 'Thương hiệu - XimenT')

@section('content')
<div class="product-container min-h-screen mx-auto container px-4 lg:px-0">
    {{-- Header --}}
    <header class="text-center py-12">
        <h1 class="text-4xl md:text-5xl font-extralight tracking-[0.2em] uppercase">Danh sách thương hiệu</h1>
        <p class="text-gray-400 mt-4 font-light italic">Các sản phẩm thuộc các thương hiệu có tầm ảnh hưởng.</p>
    </header>

    {{-- Brand Sections --}}
    <div class="space-y-24 pb-24">
        @foreach($brands as $brand)
            @if($brand->products->count() > 0)
            <div class="brand-section">
                {{-- Brand Header --}}
                <div class="flex flex-col items-center mb-8">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-16 mb-4 filter grayscale hover:grayscale-0 transition-all duration-500">
                    @endif
                    <h2 class="text-2xl md:text-3xl font-light tracking-widest uppercase border-b border-black pb-2">
                        {{ $brand->name }}
                    </h2>
                </div>

                {{-- Product Carousel for Brand --}}
                <div class="relative px-10 md:px-12 group/swiper">
                    <div class="swiper brandProductSwiper-{{ $brand->id }} !pb-8">
                        <div class="swiper-wrapper">
                            @foreach($brand->products as $product)
                                <div class="swiper-slide">
                                    <div class="bg-white flex flex-col rounded-md shadow-sm relative border hover:border-gray-300 transition-all duration-300 group/card">
                                        <a href="{{ route('product.detail', $product->slug) }}" class="rounded-t-md block overflow-hidden">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('storage/empty/empty-image-product.png') }}"                               
                                                alt="{{ $product->name }}"
                                                class="w-full aspect-[4/5] object-cover object-top group-hover/card:scale-105 transition-transform duration-500" />
                                            
                                            <div class="p-4">
                                                <div class="group-hover/card:hidden transition-all duration-300">
                                                    <h3 class="text-base font-bold text-slate-800 line-clamp-1">
                                                        {{ $product->name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-2">
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
                                                   
                                                <div class="hidden group-hover/card:block transition-all duration-300 mt-0">
                                                    <h3 class="text-sm font-bold text-slate-800">Còn hàng:</h3>
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        @foreach ($product->variants as $variant)
                                                            <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 rounded text-slate-600 border border-gray-200">
                                                                {{ $variant->size->name ?? '' }}
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

                    {{-- Navigation Buttons --}}
                    <div class="swiper-button-next next-{{ $brand->id }} !text-black after:!text-xl transition-all opacity-0 group-hover/swiper:opacity-100 !-right-2 md:!right-4"></div>
                    <div class="swiper-button-prev prev-{{ $brand->id }} !text-black after:!text-xl transition-all opacity-0 group-hover/swiper:opacity-100 !-left-2 md:!left-4"></div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($brands as $brand)
            @if($brand->products->count() > 0)
            new Swiper(".brandProductSwiper-{{ $brand->id }}", {
                slidesPerView: 2,
                spaceBetween: 16,
                centerInsufficientSlides: true,
                navigation: {
                    nextEl: ".next-{{ $brand->id }}",
                    prevEl: ".prev-{{ $brand->id }}",
                },
                breakpoints: {
                    640: { slidesPerView: 3, spaceBetween: 24 },
                    1024: { slidesPerView: 5, spaceBetween: 32 },
                }
            });
            @endif
        @endforeach
    });
</script>
<style>
    .swiper-button-next, .swiper-button-prev {
        background-color: rgba(255, 255, 255, 0.8);
        width: 40px !important;
        height: 60px !important;
        top: 40% !important;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid #e2e8f0;
    }
    .swiper-button-next:after, .swiper-button-prev:after {
        font-weight: bold;
    }
    @media (max-width: 768px) {
        .swiper-button-next, .swiper-button-prev {
            display: none !important;
        }
        .relative.group\/swiper {
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>
@endpush