<div class="modal-detail-brand">
<x-my-modal name="modal-detail-brand-{{ $brand->id }}" maxWidth="md">
    <x-slot name="header">
        <h3 class="text-gray-900 text-2xl font-bold">Chi tiết thương hiệu</h3>
        <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết: <span class="font-semibold text-indigo-600">{{ $brand->name }}</span></p>
    </x-slot>
    <x-slot name="body">
        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" class="brand-edit-form" enctype="multipart/form-data" id="form-edit-brand-{{ $brand->id }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-8 my-8">
                <div class="space-y-6">
                    <!-- name brand -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên thương hiệu <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $brand->name) }}" required disabled
                            placeholder="Ví dụ: Nike"
                            class="brand-name-input  w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    </div>

                    <!-- slug -->
                    <input type="hidden" name="slug" value="{{ old('slug', $brand->slug) }}" class="brand-slug-input">
             
                   <!-- logo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Logo<span class="text-red-500">*</span></label>
                        
                       <div class="flex items-start gap-4">
                            <div id="image-preview-{{ $brand->id }}" class="w-24 h-24 rounded-xl border-2 border-dashed border-black flex items-center justify-center bg-gray-50 flex-shrink-0 overflow-hidden">
                                <svg class="image-placeholder w-8 h-8 text-gray-300 {{ $brand->logo ? 'hidden' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <img src="{{ $brand->logo ? asset('storage/' . $brand->logo) : '' }}" 
                                     alt="Preview" 
                                     class="preview-img w-full h-full object-cover {{ $brand->logo ? '' : 'hidden' }}">
                            </div>
                            <div class="flex-1">
                                <label for="image-{{ $brand->id }}" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Chọn ảnh
                                </label>
                                <input type="file" id="image-{{ $brand->id }}" name="image" class="hidden image-input" accept="image/*" disabled>
                                <p class="text-xs text-gray-400 mt-2">Dung lượng tối đa 10MB. Ảnh này dùng làm logo cho thương hiệu.</p>
                            </div>
                        </div>
                    </div>
            </div>            
        </form>
        <form id="form-delete-{{ $brand->id}}" action="{{ route('admin.brands.destroy', $brand->id) }}" method="post">
            @csrf
            @method('DELETE')
        </form>
    </x-slot>
    <x-slot name="footer">
          <div class="pt-6 border-t border-gray-100 flex gap-4">
                <button type="submit" form="form-edit-brand-{{ $brand->id }}" class="btn-accept-edit hidden px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
                    Cập nhật
                </button>
                <button type="button" class="btn-cancel-edit hidden px-6 py-3 rounded-xl text-gray-500 text-sm font-bold bg-gray-100 hover:bg-gray-200 transition-all transform hover:-translate-y-0.5">
                    Hủy bỏ
                </button>
                <button type="button" class="btn-edit-brand px-8 py-3 rounded-xl text-white text-sm font-bold bg-[#09090a] hover:bg-gray-800 shadow-lg shadow-gray-200 transition-all whitespace-nowrap transform hover:-translate-y-0.5">
                    Chỉnh sửa
                </button>
                <button type="submit" form="form-delete-{{ $brand->id}}" class="btn-delete-brand px-8 py-3 rounded-xl text-red-600 text-sm font-bold bg-red-50 border border-red-100 hover:bg-red-600 hover:text-white transition-all transform hover:-translate-y-0.5">
                    Xóa
                </button>
    </x-slot>
</x-my-modal>
</div>

@pushOnce('scripts')
    @vite(['resources/js/admin/brand/brand-detail.js']);
@endPushOnce
