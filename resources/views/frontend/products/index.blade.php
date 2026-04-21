@extends('frontend.layouts.app')
@section('title', 'Danh sách sản phẩm')
@section('content')
<div class="product-container min-h-screen mx-auto container px-4 lg:px-0">
    <header class="flex justify-between">
        <h1 class="text-3xl md:text-4xl font-light mb-4 mt-4">Danh sách sản phẩm</h1>
    </header>

    <form action="{{ route('product.filter_product') }}" method="GET" id="filter-form">
        <div class="mt-3 filter flex justify-between gap-4">
            <div>
                <span class="text-xl">Lọc theo: </span>
                <select name="category" id="category" onchange="this.form.submit()" class="border border-gray-700 rounded-md text-lg">
                    <option value="">Danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="brand" id="brand" onchange="this.form.submit()" class="border border-gray-700 rounded-md text-lg ml-2">
                    <option value="">Thương hiệu</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>   

                <span class="text-xl ml-4 border-l border-black pl-4">Sắp xếp theo: </span>
                <select name="sort" id="sort" onchange="this.form.submit()" class="border border-gray-700 rounded-md text-lg">
                    <option value="">Mặc định</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                </select>
            </div>
        
            <div class="serach-bar w-1/4 flex border-b border-gray-600">
                <button type="submit" class="border-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm" class="w-full border-none bg-transparent focus:outline-none pl-2">
            </div>
        </div>
    </form>

    <div class="mt-8 list-product h-full">
        <section aria-labelledby="products-heading">
        <div class="w-full">
            <ul class="grid grid-cols-2 gap-4 md:gap-8 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($products as $product)
                <li class="bg-white flex flex-col rounded-md shadow-sm relative border hover:border-gray-300 transition-all duration-300 h-full">
                    <a href="{{ route('product.detail', $product->slug) }}" class="rounded-t-md flex flex-col h-full group overflow-hidden">
                        
                        <div class="relative w-full aspect-[4/5] overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('storage/empty/empty-image-product.png') }}"                               
                                alt="{{ $product->name }}"
                                class="absolute inset-0 w-full h-full object-cover object-top transition-all duration-500 {{ $product->images->count() > 0 ? 'group-hover:opacity-0' : '' }} group-hover:scale-105" />
                            
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="absolute inset-0 w-full h-full object-cover object-top opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:scale-110" />
                            @endif
                        </div>

                        <div class="p-3 md:p-4 flex-grow flex flex-col justify-between">
                            <div class="group-hover:hidden transition-all duration-300">
                                <h3 class="text-xl md:text-base font-bold text-slate-800 line-clamp-1">
                                    {{ $product->name }}
                                </h3>

                                <div class="flex items-center gap-2 mt-2">
                                    @if($product->variants->where('stock_quantity', '>', 0)->count() > 0)
                                        @if($product->variants->where('discount_price', '>', 0)->first())
                                            <p class="text-sm md:text-base font-bold text-red-600">
                                                {{ number_format($product->variants->min('discount_price'), 0, ',', '.') }}đ
                                            </p>
                                            <p class="text-xs text-gray-400 line-through">
                                                {{ number_format($product->variants->min('price'), 0, ',', '.') }}đ
                                            </p>
                                        @else
                                            <p class="text-sm md:text-base font-bold text-slate-700">
                                                {{ number_format($product->variants->min('price'), 0, ',', '.') }}đ
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-sm md:text-base font-bold text-red-600">Hết hàng</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Thông tin khi hover --}}
                            <div class="hidden group-hover:block transition-all duration-300">
                                @if($product->variants->where('stock_quantity', '>', 0)->count() > 0)
                                    <h3 class="text-sm md:text-base font-bold text-slate-800 line-clamp-1">
                                        Còn hàng:
                                    </h3>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach ($product->variants->where('stock_quantity', '>', 0) as $variant)
                                            <span class="text-xs px-2 py-1 bg-gray-100 rounded text-slate-600">
                                                {{ $variant->size->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <h3 class="text-sm md:text-base font-bold text-red-600">
                                        Đã hết hàng
                                    </h3>
                                @endif
                            </div>
                        </div>
                    </a>    
                </li>
                @endforeach
            </ul>
        </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/frontend/products/common.js')
@endpush