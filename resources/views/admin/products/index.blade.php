@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
   <div class="flex justify-between items-end mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý sản phẩm</h2>
            <p class="text-md text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $products->count() }}</span> sản phẩm</p>
        </div>

        <div>
            <button 
                x-data
                @click="$dispatch('open-modal', 'create-product-modal')"
                class="cursor-pointer block px-4 py-2 rounded-lg text-white text-md font-medium bg-[#09090b] hover:bg-gray-800 transition-all"
            >
                Thêm sản phẩm
            </button>
            @include('admin.products.create')
        </div> 
    </div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-5 mb-3">
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng sản sản phẩm</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $products->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đang được bán</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $productsInStock->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Ngừng bán</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $productsOutOfStock->count() }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        {{-- Search Bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-xl font-semibold text-gray-700">Danh sách sản phẩm</h3>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    id="search-input"
                    type="text"
                    placeholder="Tìm kiếm sản phẩm..."
                    class="pl-9 pr-4 py-2 text-md border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64"
                    onkeyup="filterTable()"
                />
            </div>
        </div>
            <div class="relative bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="w-full text-left rtl:text-right text-body">
                    <thead>
                        <tr class="bg-gray-50/50  text-lg font-bold border-b border-gray-50">
                            <th class="px-8 py-4">Sản phẩm</th>
                            <th class="px-8 py-4">Phân loại / Thương hiệu</th>
                            <th class="px-8 py-4">Giá bán hiện tại</th>
                            <th class="px-8 py-4">Kho hàng</th>
                            <th class="px-8 py-4">Trạng thái</th>
                            <th class="px-8 py-4 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="category-tbody">              
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden border border-gray-100 flex-shrink-0 bg-gray-50">
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{$product->name}}" />
                                    </div>
                                    <div>
                                        <p class="text-md font-bold text-gray-800">{{ $product->name }}</p>
                                        <p class=" text-md text-gray-400 mt-0.5">SLUG: {{ $product->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-lg text-md font-bold bg-gray-100 text-gray-600 block w-fit mb-1">{{ $product->category->name }}</span>
                                <span class="text-md text-gray-400 font-medium">Thương hiệu: <span class="text-gray-600">{{ $product->brand->name }}</span></span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-md font-black text-gray-800">
                                    {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }} VNĐ
                                </p>
                                @if($product->variants->first() && $product->variants->first()->discount_price > 0)
                                    <p class=" text-md text-indigo-500 font-bold">Giảm chỉ còn: {{ number_format($product->variants->first()->discount_price, 0, ',', '.') }} VNĐ</p>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1">
                                    @php $totalStock = $product->variants->sum('stock_quantity'); @endphp
                                    <span class="text-md font-bold {{ $totalStock > 0 ? 'text-gray-700' : 'text-red-500' }}">
                                        {{ $totalStock }} cái
                                    </span>
                                    <p class=" text-md text-gray-400">{{ $product->variants->count() }} phân loại (Size)</p>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @if($product->is_active == 1)
                                    <span class="flex items-center gap-1.5 text-green-600 font-bold  text-md bg-green-50 px-3 py-1.5 rounded-full w-fit">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                        Đang bán
                                </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-red-500 font-bold  text-md bg-red-50 px-3 py-1.5 rounded-full w-fit">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Ngừng bán
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button x-data @click="$dispatch('open-modal', 'detail-product-{{$product->id}}')"
                                    class="btn-open-detail mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white text-md font-medium border-none outline-none tracking-wide bg-[#09090a] hover:bg-gray-300 hover:text-black transition-all">
                                    Xem chi tiết
                                </button>
                            @include('admin.products.detail')        
                            </td>                  
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
