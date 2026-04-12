@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm - XimenT Admin')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Sửa sản phẩm</h2>
            <p class="text-sm text-gray-500 mt-1">Cập nhật thông tin chi tiết cho sản phẩm: <span class="font-bold text-indigo-600">{{ $product->name }}</span></p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-4">
            <p class="text-sm font-semibold text-red-700 mb-2">Vui lòng kiểm tra lại:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="text-base font-semibold text-gray-700 pb-3 border-b border-gray-100">Thông tin cơ bản</h3>
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Tên sản phẩm <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Mô tả</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        {{-- Classification & Price --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="text-base font-semibold text-gray-700 pb-3 border-b border-gray-100">Phân loại & Giá</h3>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Danh mục</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1.5">Thương hiệu</label>
                    <select id="brand_id" name="brand_id" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1.5">Giá tham khảo (₫)</label>
                <div class="relative">
                    <input type="number" id="base_price" name="base_price" value="{{ old('base_price', $product->base_price) }}" class="w-full pl-4 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₫</span>
                </div>
            </div>
        </div>

        {{-- Variants Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-700">Biến thể & Kho hàng</h3>
                <button type="button" onclick="addVariantRow()" class="text-xs font-bold text-indigo-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4" /></svg> Thêm dòng
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-400 text-[11px] uppercase font-bold tracking-wider">
                            <th class="pb-3 pr-4">Kích cỡ</th>
                            <th class="pb-3 pr-4">Giá bán (₫)</th>
                            <th class="pb-3 pr-4">Số lượng kho</th>
                            <th class="pb-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="variant-container">
                        @foreach ($product->variants as $index => $variant)
                        <tr class="variant-row border-t border-gray-50">
                            <td class="py-4 pr-4">
                                <select name="variants[{{ $index }}][size_id]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                                    @foreach($product->category->sizes as $size)
                                        <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-4 pr-4">
                                <input type="number" name="variants[{{ $index }}][price]" value="{{ $variant->price }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm">
                            </td>
                            <td class="py-4 pr-4">
                                <input type="number" name="variants[{{ $index }}][stock_quantity]" value="{{ $variant->stock_quantity }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm">
                            </td>
                            <td class="py-4 text-right">
                                <button type="button" onclick="removeVariantRow(this)" class="text-red-400 hover:text-red-600 remove-btn {{ $product->variants->count() <= 1 ? 'hidden' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Images & Album Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h3 class="text-base font-semibold text-gray-700 pb-3 border-b border-gray-100">Hình ảnh & Bộ sưu tập</h3>
            
            {{-- Main Image --}}
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700">Ảnh đại diện hiện tại</label>
                <div class="flex items-start gap-4">
                    <div class="w-32 h-32 rounded-2xl border border-gray-100 shadow-sm overflow-hidden bg-gray-50 flex items-center justify-center">
                        @if ($product->image)
                            <img id="image-preview" src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-gray-300">No Image</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label for="image" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg> Thay đổi ảnh chính
                        </label>
                        <input type="file" id="image" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                        <p class="text-xs text-gray-400 mt-2 italic">* Để trống nếu không muốn thay đổi. Tối đa 10MB.</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-50">

            {{-- Gallery/Album --}}
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700">Album ảnh chi tiết (Hiện tại)</label>
                <div class="grid grid-cols-6 gap-4">
                    @foreach ($product->images as $img)
                        <div class="relative group aspect-square rounded-xl border border-gray-100 overflow-hidden bg-gray-50 shadow-sm transition-all" id="gallery-item-{{ $img->id }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            <button type="button" onclick="deleteGalleryImage({{ $img->id }})" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    @endforeach
                    @for ($i = $product->images->count(); $i < 5; $i++)
                        <div class="aspect-square rounded-xl border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center text-gray-200">
                             <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endfor
                </div>

                <div class="mt-4">
                    <label for="gallery" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 bg-white hover:bg-gray-50 rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4" /></svg> Tải lên ảnh mới
                    </label>
                    <input type="file" id="gallery" name="gallery[]" class="hidden" multiple accept="image/*" onchange="previewGallery(event)">
                    <div id="new-gallery-preview" class="grid grid-cols-5 gap-3 mt-4"></div>
                </div>
            </div>
        </div>

        {{-- Hidden Fields --}}
        <input type="hidden" name="slug" id="slug" value="{{ $product->slug }}">

        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">Huỷ bỏ</a>
            <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md hover:shadow-lg">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>

<script>
    // Variant Management
    let variantIndex = {{ $product->variants->count() }};
    window.currentSizes = [];

    // Initialize sizes for the current category on page load
    window.onload = function() {
        const initialCategoryId = document.getElementById('category_id').value;
        if (initialCategoryId) {
            const url = `{{ url('admin/get-sizes-by-category') }}/${initialCategoryId}`;
            console.log('Edit Page - Initializing sizes from:', url);
            fetch(url)
                .then(response => response.json())
                .then(sizes => {
                    console.log('Edit Page - Initial sizes:', sizes);
                    window.currentSizes = sizes;
                })
                .catch(error => console.error('Error loading initial sizes:', error));
        }
    };

    function addVariantRow() {
        const container = document.getElementById('variant-container');
        if (!container) return;
        
        let sizeOptions = '';
        if (window.currentSizes && window.currentSizes.length > 0) {
            sizeOptions = window.currentSizes.map(size => 
                `<option value="${size.id}">${size.name}</option>`
            ).join('');
        } else {
            sizeOptions = '<option value="">Chọn danh mục trước</option>';
        }

        const newRowHTML = `
            <tr class="variant-row border-t border-gray-50">
                <td class="py-4 pr-4">
                    <select name="variants[${variantIndex}][size_id]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg">
                        ${sizeOptions}
                    </select>
                </td>
                <td class="py-4 pr-4">
                    <input type="number" name="variants[${variantIndex}][price]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm">
                </td>
                <td class="py-4 pr-4">
                    <input type="number" name="variants[${variantIndex}][stock_quantity]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm">
                </td>
                <td class="py-4 text-right">
                    <button type="button" onclick="removeVariantRow(this)" class="text-red-400 hover:text-red-600 remove-btn">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            </tr>
        `;
        
        container.insertAdjacentHTML('beforeend', newRowHTML);
        variantIndex++;
    }

    function removeVariantRow(btn) {
        btn.closest('tr').remove();
    }

    // Category-Size Sync Logic
    document.getElementById('category_id').addEventListener('change', function() {
        try {
            const categoryId = this.value;
            const variantRows = document.querySelectorAll('.variant-row');
            
            let hasData = false;
            variantRows.forEach(row => {
                const inputs = row.querySelectorAll('input[type="number"]');
                if (inputs.length >= 2) {
                    if (inputs[0].value || inputs[1].value) hasData = true;
                }
            });

            if (hasData && !confirm('Thay đổi danh mục sẽ xóa toàn bộ biến thể hiện tại. Bạn có chắc chắn?')) {
                this.value = this.dataset.oldValue || "{{ $product->category_id }}";
                return;
            }

            this.dataset.oldValue = categoryId;

            if (!categoryId) {
                window.currentSizes = [];
                document.getElementById('variant-container').innerHTML = '<tr><td colspan="4" class="py-4 text-center text-gray-400">Vui lòng chọn danh mục</td></tr>';
                return;
            }

            const url = `{{ url('admin/get-sizes-by-category') }}/${categoryId}`;
            console.log('Edit Page - Fetching sizes from:', url);

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(sizes => {
                    console.log('Edit Page - Received sizes:', sizes);
                    window.currentSizes = sizes;
                    const container = document.getElementById('variant-container');
                    container.innerHTML = '';
                    variantIndex = 0;
                    addVariantRow();
                })
                .catch(error => {
                    console.error('Edit Page - Fetch error:', error);
                    alert('Không thể lấy danh sách kích cỡ. Lỗi: ' + error.message);
                });
        } catch (e) {
            console.error('Lỗi khi đổi danh mục:', e);
            alert('Có lỗi xảy ra: ' + e.message);
        }
    });

    // Image previews
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('image-preview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    }

    function previewGallery(event) {
        const preview = document.getElementById('new-gallery-preview');
        preview.innerHTML = '';
        for (let file of event.target.files) {
            const reader = new FileReader();
            const wrap = document.createElement('div');
            wrap.className = 'aspect-square rounded-xl border border-gray-100 overflow-hidden';
            reader.onload = e => wrap.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            reader.readAsDataURL(file);
            preview.appendChild(wrap);
        }
    }

    // AJAX Delete Gallery Image
    function deleteGalleryImage(id) {
        if (confirm('Bạn có chắc chắn muốn xóa ảnh này không?')) {
            fetch(`/admin/products/gallery/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    document.getElementById(`gallery-item-${id}`).remove();
                }
            });
        }
    }
    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.variant-row');
        const buttons = document.querySelectorAll('.remove-btn');
        buttons.forEach(btn => btn.disabled = rows.length === 1);
    }
</script>
@endsection
