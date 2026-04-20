@extends('frontend.layouts.app')

@section('content')
<div class="product-container h-screen mx-auto container">
    <header class="flex justify-between">
        <h1 class="text-3xl mt-2">Danh sách sản phẩm</h1>
    </header>
    <body>
        <div class="mt-3 filter flex justify-between">
            <div>
                <span class="text-xl">Lọc theo: </span>
                <select name="category" id="category" class="border border-gray-700 rounded-md text-lg">
                    <option value="">Danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="brand" id="brand" class="border border-gray-700 rounded-md text-lg">
                    <option value="">Thương hiệu</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>   

                <span class="text-xl ml-2 border-l border-black pl-2">Sắp xếp theo: </span>
                <select name="sort" id="sort" class="border border-gray-700 rounded-md text-lg">
                    <option value="">Mặc định</option>
                    <option value="price_asc">Giá tăng dần</option>
                    <option value="price_desc">Giá giảm dần</option>
                    <option value="name_asc">Tên A-Z</option>
                    <option value="name_desc">Tên Z-A</option>
                </select>
            </div>
           
            <div class="serach-bar w-1/4 flex border-b border-gray-600">
                <button class="border-none border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
                <input type="text" placeholder="Tìm kiếm sản phẩm" class="w-full border-none border-gray-700 text-lg">
            </div>
        </div>
        <div class=" mt-3 list-product h-full">
            <section class="mt-6 px-4 md:px-8" aria-labelledby="products-heading">
            <div class="mx-auto max-w-4xl lg:max-w-7xl">
                <ul class="grid grid-cols-2 gap-4 md:gap-8 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($products as $product)
                    <li class="bg-white flex flex-col rounded-md shadow-sm relative border hover:border-gray-300 transition-all duration-300">
                        <a href="{{ route('product.detail', $product->slug) }}" class="rounded-t-md block overflow-hidden group">
                            <img src="{{ $product->avatar ? asset('storage/' . $product->avatar) : 'storage/empty/empty-image-product.png'}}" 
                                alt="{{ $product->name }}"
                                class="w-full aspect-[4/5] object-cover object-top group-hover:scale-105 transition-transform duration-500 " />

                            <div class="p-3 md:p-4">

                            <div class="group-hover:hidden transition-all">
                                <h3 class="text-xl md:text-base font-bold text-slate-800 line-clamp-1">
                                    {{ $product->name }}
                                </h3>

                                <div class="flex items-center gap-2 mt-2 ">
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
                                </div>
                            </div>
                               

                                <div class="hidden group-hover:inline transition-all mt-2">
                                    <h3 class="text-sm md:text-base font-bold text-slate-800 line-clamp-1">
                                        Còn hàng:
                                    </h3>
                                   @foreach ($product->variants as $variant)
                                        <span class="text-xl mt-2">
                                            {{ $variant->size->name }}{{ !$loop->last ? ',' : '' }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </a>    
                    </li>
                    @endforeach
                </ul>
            </div>
            </section>
        </div>
    </body>
   
</div>

@endsection
