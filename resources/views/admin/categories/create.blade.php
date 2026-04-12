
<div id="modal-create-category" 
    class="hidden fixed inset-0 z-[1000] transition-opacity duration-300 opacity-0 bg-black/50 backdrop-area"
    data-sizes-store-url="{{ route('admin.sizes.store') }}"
    data-has-errors="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="fixed inset-0 p-4 flex items-center justify-center overflow-auto pointer-events-none backdrop-area">
        <div class="modal-content w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 relative transform transition-all duration-300 scale-90 opacity-0 translate-y-4 pointer-events-auto">
            <div class="flex items-center pb-4 border-b border-gray-100">
                <div class="flex-1">
                    <h3 class="text-gray-900 text-2xl font-bold">Thêm danh mục mới</h3>
                    <p class="text-sm text-gray-500 mt-1">Cấu hình thông tin cơ bản và kích cỡ cho danh mục</p>
                </div>

                <button id="close-create-category" type="button" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
            </div>
            
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 animate-shake">
                    <div class="flex items-center gap-2 text-red-600 font-bold mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Cần kiểm tra lại:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-500 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                @csrf
                <div class="grid grid-cols-1 gap-8 my-8">
                    {{-- Thông tin cow bản --}}
                    <div class="space-y-6">

                        <!-- name category -->
                        <div>
                            <label for="category_name" class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="category_name" required
                                placeholder="Ví dụ: Áo thun nam"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                        </div>

                        <!-- slug -->
                        <div>
                            <input type="text" name="slug" id="category_slug" required hidden>
                        </div>                     
                 
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold text-gray-700">Kích cỡ áp dụng (Sizes)</label>                          
                        </div>

                        <!--  add new size-->
                        <div class="flex items-center gap-2">  
                                <input type="text" id="size_name"
                                placeholder="Ví dụ: XL"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                                <button type="button" id="submit-new-size" class="bg-[#09090a] rounded-lg text-white px-4 py-2 text-sm font-bold
                                    hover:bg-white hover:text-[#09090a] border hover:border-[#09090a] transition-all whitespace-nowrap">Thêm mới
                                </button>
                        </div>
                        <!-- choooooose size -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 min-h-[200px]" id="size-container">   
                            @if(isset($sizes) && $sizes->count() > 0)
                                <div class="grid grid-cols-3 gap-3" id="size-checkbox-grid">
                                    @foreach($sizes as $size)
                                        <label class="group relative flex items-center justify-center p-3 rounded-xl border border-white bg-white hover:border-blue-300 hover:shadow-md transition-all cursor-pointer">
                                            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" class="hidden peer">
                                            <span class="text-sm font-bold text-gray-600 peer-checked:text-blue-600 transition-colors">{{ $size->name }}</span>
                                            {{-- Overlay checkmark --}}
                                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-blue-500 rounded-xl pointer-events-none transition-all"></div>
                                            <div class="absolute top-1 right-1 opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div id="no-size-message" class="flex flex-col items-center justify-center h-full text-gray-400 opacity-60">
                                    <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-[10px]">Chưa có dữ liệu Size</p>
                                </div>
                                <div class="grid grid-cols-3 gap-3 hidden" id="size-checkbox-grid"></div>
                            @endif
                        </div>
                        <p class="mt-3 text-[11px] text-gray-400 italic">* Các size này sẽ xuất hiện khi bạn thêm sản phẩm vào danh mục này.</p>

                    </div>
                    
                </div>
                
                <!-- accept or dont -->
                <div class="pt-6 border-t border-gray-100 flex  gap-4">
                    <button id="close-create-category" type="button"
                        class="px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-gray-100 hover:bg-gray-200 hover:text-gray-700 transition-all">
                        Hủy bỏ
                    </button>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl text-white text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-200 transform hover:-translate-y-0.5 transition-all">
                        Lưu danh mục
                    </button>
                </div>            
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/js/admin/category-modal.js', 'resources/js/admin/category-create.js'])
@endpush
