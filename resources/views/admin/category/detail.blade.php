<div id="modal-detail-category-{{ $category->id }}" 
    class="modal-detail-category hidden fixed inset-0 z-[1000] transition-opacity duration-300 opacity-0 bg-black/50 backdrop-area"
    data-category-id="{{ $category->id }}"
    data-sizes-store-url="{{ route('admin.sizes.store') }}">
    <div class="fixed inset-0 p-4 flex items-center justify-center overflow-auto pointer-events-none backdrop-area">
        <div class="modal-content w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 relative transform transition-all duration-300 scale-90 opacity-0 translate-y-4 pointer-events-auto">
            <div class="flex items-center pb-4 border-b border-gray-100">
                <div class="flex-1">
                    <h3 class="text-gray-900 text-2xl font-bold">Chi tiết danh mục</h3>
                    <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết: <span class="font-semibold text-indigo-600">{{ $category->name }}</span></p>
                </div>

                <button type="button" class="btn-close-detail p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="category-edit-form">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-8 my-8">
                    <div class="space-y-6">
                        <!-- name category -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}" required disabled
                                placeholder="Ví dụ: Áo thun nam"
                                class="category-name-input  w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                        </div>

                        <!-- slug -->
                        <input type="hidden" name="slug" value="{{ old('slug', $category->slug) }}" class="category-slug-input">
                 
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold text-gray-700">Kích cỡ áp dụng (Sizes)</label>                          
                        </div>

                        <!--  add new size-->
                        <div class="add-new-size flex items-center gap-2 hidden" >  
                            <input type="text" class="size-name-input w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm" placeholder="Ví dụ: XL">
                            <button type="button" class="btn-submit-new-size bg-[#09090a] rounded-lg text-white px-4 py-2 text-sm font-bold hover:bg-white hover:text-[#09090a] border hover:border-[#09090a] transition-all whitespace-nowrap">
                                Thêm mới
                            </button>
                        </div>

                        <!-- choose size -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 min-h-[200px] size-container" >   
                            @php
                                $categorySizeIds = old('sizes', $category->sizes->pluck('id')->toArray());
                            @endphp
                            <div class="grid grid-cols-3 gap-3 size-checkbox-grid">
                                @foreach($sizes as $size)
                                    <label class="group relative flex items-center justify-center p-3 rounded-xl border border-white bg-white hover:border-blue-300 hover:shadow-md transition-all cursor-pointer">
                                        <input disabled type="checkbox" name="sizes[]" value="{{ $size->id }}" class="choose-size hidden peer" {{ in_array($size->id, $categorySizeIds) ? 'checked' : '' }} >
                                        <span class="text-sm font-bold text-gray-600 peer-checked:text-blue-600 transition-colors">{{ $size->name }}</span>
                                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-blue-500 rounded-xl pointer-events-none transition-all"></div>
                                        <div class="absolute top-1 right-1 opacity-0 peer-checked:opacity-100 transition-opacity">
                                            <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-gray-100 flex gap-4">
                    <button type="button" class="btn-cancel-edit hidden px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-white border border-whi hover:bg-gray-200 hover:text-gray-700 transition-all transform hover:-translate-y-0.5 transition-all"">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="btn-accept-edit hidden px-8 py-3 rounded-xl text-white text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-200 transform hover:-translate-y-0.5 transition-all">
                        Cập nhật
                    </button>   
                    <button type="button" class="btn-edit-category px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-white hover:text-[#09090a] border border-[#09090a] hover:bg-white hover:text-[#09090a] transition-all whitespace-nowrap transform hover:-translate-y-0.5 transition-all"">
                        Chỉnh sửa
                    </button>
                    <button type="submit" form="form-delete-{{ $category->id}}" class="btn-delete-category px-8 py-3 rounded-xl text-black text-sm font-bold bg-white border border-black hover:bg-black hover:text-red-500 transform hover:-translate-y-0.5 transition-all">
                        Xóa
                    </button>
                </div>            
            </form>
            <form id="form-delete-{{ $category->id}}" action="{{ route('admin.categories.destroy', $category->id) }}" method="post">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@pushOnce('scripts')
    @vite(['resources/js/admin/category/category-detail.js']);
@endPushOnce
