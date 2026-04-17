@extends('admin.layouts.app')

@section('title', 'Quản lý khách hàng - XimenT Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
   <div class="flex justify-between items-end mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý khách hàng</h2>
            <p class="text-md text-gray-500 mt-1">Tổng cộng <span class="font-semibold text-indigo-600">{{ $customers->count() }}</span> khách hàng</p>
        </div>
    </div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-5 mb-3">
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tổng khách hàng</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $customers->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-green-500 uppercase tracking-wider font-semibold">Khách hàng có tài khoản</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $customers->whereNotNull('user_id')->count(); }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
            <p class="text-xs text-red-400 uppercase tracking-wider font-semibold">Khách hàng vãng lai</p>
            <p class="text-3xl font-bold text-red-500 mt-2">{{ $customers->whereNull('user_id')->count(); }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        {{-- Search Bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-xl font-semibold text-gray-700">Danh sách khách hàng</h3>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    id="search-input"
                    type="text"
                    placeholder="Tìm kiếm khách hàng..."
                    class="pl-9 pr-4 py-2 text-md border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 w-64"
                    onkeyup="filterTable()"
                />
            </div>
        </div>
            <div class="relative bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="w-full text-left rtl:text-right text-body">
                    <thead>
                        <tr class="bg-gray-50/50  text-lg font-bold border-b border-gray-50">
                            <th class="px-8 py-4 text-center">STT</th>
                            <th class="px-8 py-4">Tên khách hàng</th>
                            <th class="px-8 py-4">Email</th>
                            <th class="px-8 py-4">Ngày đăng ký</th>
                            <th class="px-8 py-4 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="category-tbody">              
                        @foreach($customers as $customer)
                        <tr>
                            <td class="px-8 py-4 text-center">{{ $customer->id }}</td>
                            <td class="px-8 py-4">{{ $customer->name }}</td>
                            <td class="px-8 py-4">{{ $customer->email }}</td>
                            <td class="px-8 py-4">{{ $customer->created_at }}</td>
                            <td class="px-8 py-4">
                                <button x-data @click="$dispatch('open-modal', 'detail-customer-modal-{{ $customer->id }}')"
                                    class="btn-open-detail mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white text-md font-medium border-none outline-none tracking-wide bg-[#09090a] hover:bg-gray-300 hover:text-black transition-all">
                                    Xem chi tiết
                                </button>
                                @include('admin.customer.detail')
                            </td>
                        </tr>

                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
