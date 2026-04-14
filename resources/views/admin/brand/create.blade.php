
<div id="modal-create-brand" 
    class="hidden fixed inset-0 z-[1000] transition-opacity duration-300 opacity-0 bg-black/50 backdrop-area"
    data-sizes-store-url="{{ route('admin.sizes.store') }}"
    data-has-errors="{{ $errors->hasBag('brand_create') ? 'true' : 'false' }}">
    <div class="fixed inset-0 p-4 flex items-center justify-center overflow-auto pointer-events-none backdrop-area">
        <div class="modal-content w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 relative transform transition-all duration-300 scale-90 opacity-0 translate-y-4 pointer-events-auto">
            <div class="flex items-center pb-4 border-b border-gray-100">
                <div class="flex-1">
                    <h3 class="text-gray-900 text-2xl font-bold">Thêm thương hiệu mới</h3>
                    <p class="text-sm text-gray-500 mt-1">Cấu hình thông tin cơ bản và logo cho thương hiệu</p>
                </div>

                <button id="close-create-brand" type="button" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
            </div>

            <form action="{{ route('admin.brands.store') }}" method="POST" id="brandForm" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-8 my-8">
                    {{-- Thông tin cow bản --}}
                    <div class="space-y-6">

                        <!-- name brand -->
                        <div>
                            <label for="brand_name" class="block text-sm font-semibold text-gray-700 mb-2">Tên thương hiệu <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="brand_name" required
                                placeholder="Ví dụ: Nike"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                        </div>

                        <!-- slug -->
                        <div>
                            <input type="text" name="slug" id="brand_slug" hidden>
                        </div>                     

                        <!-- logo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh đại diện <span class="text-red-500">*</span></label>
                            
                           <div class="flex items-start gap-4">
                                <div id="image-preview" class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center bg-gray-50 flex-shrink-0 overflow-hidden">
                                    <svg id="image-placeholder" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <img id="preview-img" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                </div>
                                <div class="flex-1">
                                    <label for="image" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        Chọn ảnh
                                    </label>
                                    <input type="file" id="image" name="image" class="hidden" accept="image/*">
                                    <p class="text-xs text-gray-400 mt-2">Dung lượng tối đa 10MB. Ảnh này dùng làm logo cho thương hiệu.</p>
                                </div>
                            </div>
                        </div>

                        
                    </div>        
                </div>
                
                <!-- accept or dont -->
                <div class="pt-6 border-t border-gray-100 flex  gap-4">
                     <button type="submit"
                        class="px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-white hover:text-[#09090a] hover:border border-black shadow-lg shadow-[#09090a]/10 transform hover:-translate-y-0.5 transition-all">
                        Lưu thương hiệu
                    </button>
                    <button id="close-create-brand" type="button"
                        class="px-6 py-3 rounded-xl text-black text-sm font-bold bg-white border border-black hover:bg-gray-200 hover:text-black transition-all transform hover:-translate-y-0.5 transition-all">
                        Hủy bỏ
                    </button>
                   
                </div>            
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/js/admin/brand/brand-modal.js', 'resources/js/admin/brand/brand-create.js'])
@endpush
