<div id="modal-create-product" 
    class="hidden fixed inset-0 z-[1000] transition-opacity duration-300 opacity-0 bg-black/50 backdrop-area">
    <div class="fixed inset-0 p-4 flex items-center justify-center overflow-auto pointer-events-none backdrop-area">
        <div class="modal-content w-full max-w-7xl h-[80vh] bg-white overflow-y-auto shadow-2xl rounded-2xl p-8 relative transform transition-all duration-300 scale-90 opacity-0 translate-y-4 pointer-events-auto">
            <div class="flex items-center pb-4 border-b border-gray-100">
                <div class="flex-1">
                    <h3 class="text-gray-900 text-2xl font-bold">Thêm sản phẩm mới</h3>
                    <p class="text-sm text-gray-500 mt-1">Cấu hình thông tin cơ bản cho sản phẩm</p>
                </div>

                <button id="close-create-product" type="button" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
            </div>          
  
            <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
                @csrf

                <div class="top flex gap-4 mt-2">
                    <div class="general-info flex-1 h-full ">
                        <h1 class="text-xl font-bold">Thông tin căn bản</h1>
                         <div class="mt-2">
                            <label for="category_name" class="block text-sm font-semibold text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="category_name" required
                                placeholder="Ví dụ: Áo thun nam"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                        </div>
                         <div class="mt-4">  
                            <label for="product_description" class="block text-sm font-semibold text-gray-700 mb-2 ">Mô tả sản phẩm <span class="text-red-500">*</span></label>
                            <textarea name="product_description" id="product_description"></textarea>
                        </div>  
                        <div class="mt-4">
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 ">Danh mục sản phẩm <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                                <option value="">Chọn danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2 ">Trạng thái sản phẩm <span class="text-red-500">*</span></label>
                            <input type="radio" name="status" id="status" value="1"> Bán
                            <input type="radio" name="status" id="status" value="0"> Ngừng bán
                        </div>                                             
                    </div>

                    <div class="flex flex-col">
                        <div class="pricing">
                            <h1 class="text-xl font-bold">Giá sản phẩm</h1>
                            <div class="flex gap-4 mt-2">
                                <div class="flex-1">
                                    <label for="base_price">Giá nhập <span class="text-red-500">*</span></label>
                                    <input type="number" name="base_price" id="base_price" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"                                        placeholder="999,999 VNĐ">

                                </div>
                                <div class="flex-1">
                                    <label for="sell_price">Giá bán <span class="text-red-500">*</span></label>
                                    <input type="number" name="sell_price" id="sell_price" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                                        placeholder="999,999 VNĐ">
                                        
                                </div>
                            </div>
                            <div class="flex gap-4 mt-4">
                                <div class="flex-1">
                                    <label for="discount_percent">Giảm giá (%)<span class="text-red-500">*</span></label>
                                    <input type="number" name="discount_percent" id="discount_percent" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                                        placeholder="10%">
                                </div>
                                <div class="flex-1">
                                    <label for="discount_amount">Giá yêu thương<span class="text-red-500">*</span></label>
                                    <input type="number" name="discount_amount" id="discount_amount" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm" 
                                        disabled
                                        placeholder="Tự động tính">
                                </div>
                            </div>
                        </div>
                     
                        <div class="media h-full mt-4 flex flex-col">
                            <h1 class="text-xl font-bold mb-4">Ảnh sản phẩm</h1>
                            
                            <div class="flex gap-6 flex-1">
                                <!-- Trái: Ảnh đại diện -->
                                <div class="w-1/3 flex flex-col">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hình đại diện <span class="text-red-500">*</span></label>
                                    <div class="relative w-full aspect-square rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center bg-gray-50 flex-shrink-0 group overflow-hidden">
                                          <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                        <img id="preview-img" src="" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                        
                                        <!-- Hover Overlay để chọn ảnh -->
                                        <label for="image" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                                            <span class="bg-white/90 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">Đổi ảnh</span>
                                        </label>
                                    </div>
                                    <input type="file" id="image" name="image" class="hidden" accept="image/*">
                                    <p class="text-[10px] text-gray-400 mt-2 text-center">Tối đa 10MB. Ảnh chính của sản phẩm.</p>
                                </div>

                                <!-- Phải: Ảnh chi tiết (Multiple) -->
                                <div class="w-2/3 flex flex-col">
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
                                    <div id="gallery-preview-container" class="grid grid-cols-3 gap-2 mt-2 empty:hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bottom mt-4 border-t border-gray-300 pt-4">
                    <div class="variants">
                        <h1 class="text-xl font-bold">Phân loại sản phẩm</h1>
                        <div class="flex gap-4">
                            <div class="size">
                                <label for="size">Kích thước</label>
                                <select name="size" id="size" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                                    <option value="">Chọn kích thước</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="quantity">
                                <label for="quantity">Số lượng</label>
                                <input type="number" name="quantity" id="quantity" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- accept or dont -->
                <div class="pt-6 border-t border-gray-100 flex  gap-4">
                     <button type="submit"
                        class="px-8 py-3 rounded-xl text-white text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-200 transform hover:-translate-y-0.5 transition-all">
                        Lưu sản phẩm
                    </button>
                    <button id="close-create-category" type="button"
                        class="px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-gray-100 hover:bg-gray-200 hover:text-gray-700 transition-all">
                        Hủy bỏ
                    </button>  
                </div>            
            </form>
            
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/js/admin/product/product-modal.js', 'resources/js/admin/product/product-create.js'])

    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js" 
            onload="CKEDITOR.replace('product_description', { baseFloatZIndex: 10005, height: 50});">
    </script>
@endpush
