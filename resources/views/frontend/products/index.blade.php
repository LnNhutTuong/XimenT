@extends('frontend.layouts.app')

@section('content')
<div class="product-container h-screen">
    <header class="flex justify-between">
        <h1>Danh sách sản phẩm</h1>
        <div class="serach-bar">
            <input type="text" placeholder="Tìm kiếm sản phẩm">
        </div>

    </header>
    <div class="grid grid-cols-5 gap-3 mx-20 mt-3">
        <div class="left-content col-span-1 border border-black p-4">
            <p class="text-center text-xl">Lọc sản phẩm</p>
            <div class="mt-2">
                <p class="text-lg font-bold">Danh mục:</p>
                <div class="flex flex-col gap-2 mt-1 mx-3"> 
                    <button class=" block w-full text-left px-3 py-2 rounded-xl  transition-colors bg-gray-200 border 
                                    hover:bg-gray-300 hover:border-black">Áo</button>
                </div>
            </div>
        </div>
        <div class="right-content col-span-4 border bg-black">L</div>
    </div>
</div>

@endsection