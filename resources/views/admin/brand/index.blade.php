@extends('admin.layouts.app')

@section('title', 'Quản lý thương hiệu - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col">
        <div class="flex justify-between mb-2">
            <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý thương hiệu</h2>
            <p class="text-sm text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $brands->count() }}</span> sản phẩm</p>
        </div>

        <div>            
            <button x-data @click="$dispatch('open-modal', 'create-brand-modal')"
                class="mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white 
                text-sm font-medium border-none outline-none tracking-wide bg-[#09090a] 
                hover:bg-gray-300 hover:text-black transition-all">
                Thêm thương hiệu
            </button>
            @include('admin.brand.create')     
        </div>
        </div>
        
           
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-5 mb-3">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng thương hiệu</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $brands->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đang có sản phẩm</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $brandwithproduct->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Không có sản phẩm</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $brandwithoutproduct->count() }}</p>
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
                    placeholder="Tìm kiếm thương hiệu..."
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
                            <th scope="col" class="px-6 py-3 text-xl font-bold text-center">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-xl font-bold text-center">
                                Logo
                            </th>
                            <th scope="col" class="px-6 py-3 text-xl font-bold text-center">
                                Tên thương hiệu
                            </th>                   
                            <th scope="col" class="px-6 py-3 text-xl font-bold text-center">
                                Số lượng sản phẩm
                            </th>
                            <th scope="col" class="px-6 py-3 text-xl font-bold text-center">
                                Hành động
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody id="brand-tbody">
                      @foreach ($brands as $brand)
                        <tr class="bg-neutral-primary border-b border-default">
                            <th scope="row" class="px-6 py-4 text-md font-bold text-center text-heading whitespace-nowrap text-center">
                                {{ $brand->id }}
                            </th>
                             <td class="px-6 py-4 text-md font-bold text-center">
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{$brand->name}}" class=" w-14 h-14 object-cover mx-auto border border-dashed border-gray-500">
                            </td>   
                             <td class="px-6 py-4 text-md font-bold text-center">
                                {{ $brand->name }}
                            </td>
                            <td class="px-6 py-4 text-md font-bold text-center">
                                {{ $products->where('brand_id', $brand->id)->count() }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="brand-action-container">      
                                    <button x-data @click="$dispatch('open-modal', 'modal-detail-brand-{{ $brand->id }}')"
                                        class="btn-open-detail mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white text-sm font-medium border-none outline-none tracking-wide bg-[#09090a] hover:bg-gray-300 hover:text-black transition-all">
                                        Xem chi tiết
                                    </button>      
                                    @include('admin.brand.detail', $brand)        
                                    
                                </div> 
                            </td>                    
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
