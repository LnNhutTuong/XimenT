@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col">
        <div class="flex justify-between">
            <div class="mb-2">
            <h2 class="text-2xl font-bold text-gray-800">Quản lý sản phẩm</h2>
            <p class="text-sm text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $products->count() }}</span> sản phẩm</p>
        </div>

        <div class="mb-2">
            <button 
                x-data
                @click="$dispatch('open-modal', 'create-product-modal')"
                class="cursor-pointer block px-4 py-2 rounded-lg text-white text-sm font-medium bg-[#09090b] hover:bg-gray-800 transition-all"
            >
                Thêm sản phẩm
            </button>

            @include('admin.products.create')
        </div>
        
           
    </div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-5 mb-3">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng sản sản phẩm</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $products->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đang có sản phẩm</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $productsInStock->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Không có sản phẩm</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $productsOutOfStock->count() }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Search Bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-700">Danh sách sản phẩm</h3>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    id="search-input"
                    type="text"
                    placeholder="Tìm kiếm sản phẩm..."
                    class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64"
                    onkeyup="filterTable()"
                />
            </div>
        </div>

        <div class="mx-4 my-4 flex gap-4">
                @foreach($products as $product)
                <div class="block w-[222px] p-6 border border-gray-400 rounded-lg shadow-xs flex flex-col col-between">
                    <a href="#">
                        <img class="rounded-lg h-40 w-36 border border-black mx-auto " src="{{ asset('storage/' . $product->image) }}" alt="{{$product->name}}" />
                    </a>
          
                    <a href="{{ route('admin.products.show', $product->id) }}">
                         <h5 class="mt-6 mb-2 text-xl font-semibold tracking-tight text-heading overflow-hidden">{{ $product->name }}</h5>
                    </a>
                    <div class="flex flex-col mt-auto ">
                        @if($product->is_active == 1)
                        <p class="mb-6 text-body font-bold text-green-600">Sản phẩm đang bán </p>
                        @else
                        <p class="mb-6 text-body font-bold text-red-600">Sản phẩm chưa bán </p>
                        @endif

                        <!-- ád -->
                        @include('admin.products.detail')        
                        <button x-data @click="$dispatch('open-modal', 'detail-product-{{$product->id}}')"
                            class="btn-open-detail mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white text-sm font-medium border-none outline-none tracking-wide bg-[#09090a] hover:bg-gray-300 hover:text-black transition-all">
                            Xem chi tiết
                        </button>
                </div>          
            </div>               
            @endforeach         
        </div>    
    </div>
</div>


@endsection
