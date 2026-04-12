@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý sản phẩm</h2>
            <p class="text-sm text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $products->count() }}</span> sản phẩm</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Thêm sản phẩm
        </a>
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
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng sản phẩm</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $products->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Đang bán</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $products->where('is_active', 1)->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Ẩn</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $products->where('is_active', 0)->count() }}</p>
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
                                Hình ảnh
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Tên sản phẩm
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Giá sản phẩm 
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Trạng thái
                            </th>
                               <th scope="col" class="px-6 py-3 font-medium ">
                                Hành động
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $product)
                        <tr class="bg-neutral-primary border-b border-default">
                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap text-center">
                                {{ $product->id }}
                            </th>
                            <td class="px-6 py-4">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover">
                            </td>
                            <td class="px-6 py-4">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ number_format($product->base_price, 0, ',', '.') }} VNĐ
                            </td>

                            @if($product->is_active)
                            <td class="px-6 py-4 text-green-500 text-center">
                                Đang bán
                            </td>
                            @else
                            <td class="px-6 py-4 text-red-500 text-center">
                                Ẩn
                            </td>
                            @endif

                             <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                        Sửa
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            Xóa
                                        </button>
                                    </form>
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