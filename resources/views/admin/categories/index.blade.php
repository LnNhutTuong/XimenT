@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý danh mục</h2>
            <p class="text-sm text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $categories->count() }}</span> sản phẩm</p>
        </div>

        <div>            
            @include('admin.categories.create')        
            <button id="open-create-category" type="button"
                class="mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white 
                text-sm font-medium border-none outline-none tracking-wide bg-[#09090a] 
                hover:bg-gray-300 hover:text-black transition-all">
                Thêm danh mục
            </button>
        </div>
           
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng danh mục</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $categories->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đang có sản phẩm</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $categorywithproduct->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Không có sản phẩm</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $categorywithoutproduct->count() }}</p>
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

        <div class="overflow-x-auto">
            

            <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-sm text-body bg-gray-50 border-b rounded-base border-default text-center">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Tên danh mục
                            </th>                   
                            <th scope="col" class="px-6 py-3 font-medium">
                                Số lượng sản phẩm
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium ">
                                Hành động
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody id="category-tbody">
                      @foreach ($categories as $category)
                        <tr class="bg-neutral-primary border-b border-default">
                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap text-center">
                                {{ $category->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $category->name }}
                            </td>   
                            <td class="px-6 py-4 text-center">
                                {{ $products->where('category_id', $category->id)->count() }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="category-action-container">            
                                    @include('admin.categories.detail', $category)        
                                    <button type="button"
                                        class="btn-open-detail mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white text-sm font-medium border-none outline-none tracking-wide bg-[#09090a] hover:bg-gray-300 hover:text-black transition-all"
                                        data-target="modal-detail-category-{{ $category->id }}">
                                        Xem chi tiết
                                    </button>
                                </div> 
                             </td>                  
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


@endsection