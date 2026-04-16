<x-my-modal name="create-category-modal" maxWidth="md">
    <x-slot name="title">
        Thêm danh mục mới
    </x-slot>
    <x-slot name="body">
        <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm" 
            data-sizes-store-url="{{ route('admin.sizes.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-8">
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
            </form>
    </x-slot>
    <x-slot name="footer">
        <button type="submit" 
            form="categoryForm" 
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all">
            Lưu danh mục
        </button>
        <button id="close-create-category" type="button"
            class="px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-gray-100 hover:bg-gray-200 hover:text-gray-700 transition-all">
            Hủy bỏ
        </button> 
    </x-slot>
</x-my-modal>
@push('scripts')
    @vite('resources/js/admin/category/category-create.js')
@endpush
