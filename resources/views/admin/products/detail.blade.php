<x-my-modal name="detail-product-{{$product->id}}">
    <x-slot name="title">
        Chi tiết sản phẩm
    </x-slot>

    <x-slot name="body">
          <form action="{{ route('admin.products.update', $product->id) }}" method="POST" id="productForm-{{ $product->id }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="top flex gap-4 mt-2">
                    <div class="general-info flex-1 h-full ">
                        <h1 class="text-xl font-bold">Thông tin căn bản</h1>

                        <!-- name -->
                         <div class="mt-2">
                            <label for="product_name_{{$product->id}}" class="block text-sm font-semibold text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="product_name_{{$product->id}}" required disabled
                                placeholder="Ví dụ: Áo thun nam"
                                class="product-name-input w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                value="{{old('name', $product->name)}}">
                        </div>
                        
                        <!-- slug -->
                        <div>
                            <input type="text" name="slug" id="product_slug_{{$product->id}}" class="product-slug-input" hidden value="{{old('slug', $product->slug)}}"> 
                        </div>             

                        <!-- des -->
                         <div class="mt-4">  
                            <label for="product_description_{{$product->id}}" class="block text-sm font-semibold text-gray-700 mb-2 ">Mô tả sản phẩm <span class="text-red-500">*</span></label>
                            <textarea name="description" id="product_description_{{$product->id}}" disabled>{{old('description', $product->description) }}</textarea>
                        </div>  

                        <!-- category -->
                        <div class="mt-4">
                            <label for="product_category_id_{{$product->id}}" class="block text-sm font-semibold text-gray-700 ">Danh mục sản phẩm <span class="text-red-500">*</span></label>
                            <select name="product_category_id" id="product_category_id_{{$product->id}}" disabled class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50">
                                <option value="">Chọn danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('product_category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- brand -->
                        <div class="mt-4">
                            <label for="product_brand_id_{{$product->id}}" class="block text-sm font-semibold text-gray-700 ">Thương hiệu sản phẩm <span class="text-red-500">*</span></label>
                            <select name="product_brand_id" id="product_brand_id_{{$product->id}}" disabled class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50">
                                <option value="">Chọn danh mục</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('product_brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- state -->
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 ">Trạng thái sản phẩm <span class="text-red-500">*</span></label>
                            <input type="radio" name="product_status" value="1" {{ old('product_status', $product->is_active) == 1 ? 'checked' : '' }} disabled> Bán
                            <input type="radio" name="product_status" value="0" {{ old('product_status', $product->is_active) == 0 ? 'checked' : '' }} disabled> Ngừng bán
                        </div>                                             
                    </div>

                    <div class="flex flex-col">
                        <div class="pricing">
                            <h1 class="text-xl font-bold">Giá sản phẩm</h1>
                            <div class="flex gap-4 mt-2">
                                <div class="flex-1">
                                    <label for="base_price_{{$product->id}}">Giá nhập <span class="text-red-500">*</span></label>
                                    <input type="text" disabled onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="base_price" id="base_price_{{$product->id}}" class="base-price w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50" placeholder="Ví dụ: 999.000 VNĐ" value="{{ number_format($product->base_price, 0, ',', '.') }} VNĐ">
                                </div>
                                <div class="flex-1">
                                    <label for="sell_price_{{$product->id}}">Giá bán <span class="text-red-500">*</span></label>
                                    <input type="type" disabled onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="sell_price" id="sell_price_{{$product->id}}" class="sell-price w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                        placeholder="Ví dụ: 999.000 VNĐ" value="{{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }} VNĐ">
                                        
                                </div>
                            </div>
                            <div class="flex gap-4 mt-4">

                                <!-- cho 1 cai function tu dong tinh thang nay dua tren sell_price and discount_amount -->
                                <div class="flex-1">
                                    <label for="discount_percent_{{$product->id}}">Giảm giá (%)</label>
                                    <input type="type" disabled onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="discount_percent" id="discount_percent_{{$product->id}}" class="discount-percent w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50"
                                        placeholder="%">
                                </div>
                                <div class="flex-1">
                                    <label for="discount_amount">Giá yêu thương<span class="text-red-500">*</span></label>
                                    <input type="type" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="discount_amount" id="discount_amount_{{$product->id}}" class="discount-amount w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm" 
                                        disabled
                                        placeholder="Tự động tính"
                                        value="{{ number_format($product->variants->first()->discount_price ?? 0, 0, ',', '.') }} VNĐ">
                                </div>
                            </div>
                        </div>
                     
                            <div class="media h-full mt-4 flex flex-col">
                                <h1 class="text-xl font-bold mb-4">Ảnh sản phẩm</h1>
                                <div class="flex gap-6 flex-1">
                                    <div class="w-1/3 flex flex-col">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hình đại diện <span class="text-red-500">*</span></label>
                                        <div class="relative w-full aspect-square rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center bg-gray-50 flex-shrink-0 group overflow-hidden">
                                            <svg class="w-5 h-5 text-indigo-500 {{ $product->image ? 'hidden' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            <img id="preview-img-{{$product->id}}" src="{{ asset('storage/'.$product->image) }}" alt="Preview" class="absolute inset-0 w-full h-full object-cover {{ $product->image ? '' : 'hidden' }}">
                                            
                                            <label for="image_{{$product->id}}" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                                <span class="bg-white/90 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">Đổi ảnh</span>
                                            </label>

                                        </div>
                                        <input type="file" id="image_{{$product->id}}" name="image" class="hidden" accept="image/*" disabled>
                                        <p class="text-[10px] text-gray-400 mt-2 text-center">Tối đa 10MB. Ảnh chính của sản phẩm.</p>
                                    </div>

                                    <div class="w-2/3 flex flex-col ">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh chi tiết (Album)</label>
                                        <label for="gallery_images" class="flex-1 border-2 border-dashed border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 hover:border-indigo-300 transition-colors cursor-pointer group">
                                            <div class="p-2 bg-white rounded-full shadow-sm group-hover:scale-110 transition-transform mb-2">
                                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-indigo-600 mb-1">Tải lên nhiều ảnh</p>
                                            <p class="text-[11px] text-gray-400 text-center">Hỗ trợ JPG, PNG, WEBP.</p>
                                            <input type="file" id="gallery_images" name="gallery_images[]" multiple class="hidden" accept="image/*">
                                        </label>
                                        
                                        <!-- Khu vực hiển thị xem trước ảnh chi tiết -->
                                       <div id="gallery-preview-container" class="grid grid-cols-3 gap-2 mt-2 max-h-24 overflow-y-auto">
                                            @if($product->images->isNotEmpty()) 
                                                @foreach($product->images as $image)
                                                    <div class="relative w-24 h-24"> 
                                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                            alt="{{ $product->name }}" 
                                                            class="w-24 h-24 object-cover rounded-lg">
                                                             <button type="button"
                                                                class="button-remove-gallery-image absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded">
                                                                x
                                                            </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="col-span-3 text-gray-500 text-sm">Không có hình ảnh chi tiết.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                
                <div class="bottom mt-4 border-t border-gray-300 pt-4 flex gap-4">
                    <div class="variants flex-1">
                        <h1 class="text-xl font-bold">Phân loại sản phẩm</h1>
                        
                        <div id="variants" class="flex flex-col gap-4">
                            @foreach ($product->variants as $variant)
                            <div class="variant-item flex gap-4">
                                <div class="size">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thước</label>
                                    <select name="sizes[]" disabled class="size-select w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50">
                                        <option value="">Chọn kích thước</option>
                                        @foreach ($product->category->sizes as $size)
                                            <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="quantity flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số lượng</label>
                                    <input type="number" min="0" name="quantities[]" value="{{ $variant->stock_quantity }}" disabled class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm bg-gray-50">
                                </div>

                                <div class="mt-2 flex items-end pb-2 gap-2">
                                    <svg class="btn-add-variant size-10 hover:cursor-pointer hover:text-green-500 transform hover:-translate-y-0.5 transition-all" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <svg class="btn-remove-variant size-10 hover:cursor-pointer hover:text-red-500 transform hover:-translate-y-0.5 transition-all" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($product->variants->isEmpty())
                            <div class="variant-item flex gap-4">
                                <div class="size">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thước</label>
                                    <select name="sizes[]" class="size-select w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                                        <option value="">Chọn kích thước</option>
                                    </select>
                                </div>

                                <div class="quantity flex-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số lượng</label>
                                    <input type="number" min="0" name="quantities[]" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                                </div>

                                <div class="mt-2 flex items-end pb-2 gap-2">
                                    <svg class="btn-add-variant size-10 hover:cursor-pointer hover:text-green-500 transform hover:-translate-y-0.5 transition-all" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <svg class="btn-remove-variant size-10 hover:cursor-pointer hover:text-red-500 transform hover:-translate-y-0.5 transition-all" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div id="hidden-size-data" class="hidden">
                            @foreach ($categories as $category)
                                <div id="sizes-for-category-{{ $category->id }}">
                                    <option value="">Chọn kích thước</option>
                                    @foreach ($category->sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                    
            </form>
            <form id="form-delete-{{ $product->id}}" action="{{ route('admin.products.destroy', $product->id) }}" method="post">
                @csrf
                @method('DELETE')
            </form>
    </x-slot>

    <x-slot name="footer">
          <div class="pt-6 border-t border-gray-100 flex gap-4">
               <button type="submit" form="productForm-{{ $product->id }}" class="btn-accept-edit hidden px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
                        Cập nhật
                    </button>
                    <button type="button" class="btn-close-modal hidden px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-gray-100 hover:bg-gray-200 transition-all transform hover:-translate-y-0.5">
                        Hủy bỏ
                    </button>
                    <button type="button" class="btn-edit-product px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
                        Chỉnh sửa
                    </button>
                    <button type="submit" form="form-delete-{{ $product->id}}" class="btn-delete-product px-8 py-3 rounded-xl text-red-600 text-sm font-bold bg-red-50 border border-red-100 hover:bg-red-600 hover:text-white transition-all transform hover:-translate-y-0.5">
                        Xóa
                    </button>
                </div>      
    </x-slot>
</x-my-modal>

@push('scripts')
    @vite(['resources/js/admin/product/product-detail.js'])

    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js" 
            onload="CKEDITOR.replace('product_description_{{$product->id}}', { baseFloatZIndex: 10005, height: 50, 
            toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            { name: 'insert', items: ['Image', 'Table'] },
            { name: 'tools', items: ['Maximize']},
        ]});">
    </script>
@endpush
