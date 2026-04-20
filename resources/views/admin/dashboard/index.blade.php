@extends('admin.layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Thẻ thống kê -->
    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl">
        <h3 class="text-indigo-100 uppercase text-xs font-bold tracking-widest">Sản phẩm</h3>
        <p class="text-4xl font-bold mt-2">{{ $productCount }}</p>
    </div>

    <div class="bg-emerald-600 rounded-2xl p-6 text-white shadow-xl">
        <h3 class="text-emerald-100 uppercase text-xs font-bold tracking-widest">Danh mục</h3>
        <p class="text-4xl font-bold mt-2">{{ $categoryCount }}</p>
    </div>

    <div class="bg-amber-600 rounded-2xl p-6 text-white shadow-xl">
        <h3 class="text-amber-100 uppercase text-xs font-bold tracking-widest">Thương hiệu</h3>
        <p class="text-4xl font-bold mt-2">{{ $brandCount }}</p>
    </div>
</div>
<div>
    <canvas id="myChart"></canvas>
</div>
<div class="mt-12 bg-white rounded-2xl shadow-sm border p-8">
    <h3 class="text-xl font-bold text-gray-800">Thông tin phân quyền</h3>
    <div class="mt-4 p-4 bg-gray-50 rounded-xl border-l-4 border-indigo-500">
        <p class="text-gray-600">Bạn đang đăng nhập với vai trò: <strong class="text-indigo-600">{{ auth()->user()->role }}</strong></p>
        <p class="text-sm text-gray-500 mt-2 italic">Hệ thống phân quyền (RBAC) đã được kích hoạt. Chỉ người dùng có role là 'admin' mới có thể thấy và truy cập vào khu vực này.</p>
    </div>
</div>
@endsection
@section('scripts')
    @vite('resources/js/admin/dashboard/dashboard.js')
@endsection
