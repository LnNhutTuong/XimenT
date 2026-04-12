@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm - XimenT Admin')

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
            <h2 class="text-2xl font-bold text-gray-800">Thêm sản phẩm mới</h2>
            <p class="text-sm text-gray-500 mt-1">Điền đầy đủ thông tin để tạo sản phẩm</p>
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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="text-base font-semibold text-gray-700 pb-3 border-b border-gray-100">Thông tin cơ bản</h3>

            {{-- Product Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Tên sản phẩm <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nhập tên sản phẩm..."
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition-all @error('name') border-red-400 @enderror"
                >
                @error('name')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Mô tả</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Nhập mô tả chi tiết sản phẩm..."
                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition-all resize-none"
                >{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- Category, Brand, Price --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="text-base font-semibold text-gray-700 pb-3 border-b border-gray-100">Phân loại & Giá</h3>

            <div class="grid grid-cols-2 gap-5">
                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Danh mục <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white @error('category_id') border-red-400 @enderror"
                    >
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Brand --}}
                <div>
                    <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1.5">Thương hiệu</label>
                    <select
                        id="brand_id"
                        name="brand_id"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white"
                    >
                        <option value="">-- Chọn thương hiệu --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Base Price --}}
            <div>
                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Giá tham khảo (₫) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="number"
                        id="base_price"
                        name="base_price"
                        value="{{ old('base_price') }}"
                        placeholder="0"
                        min="0"
                        class="w-full pl-4 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('base_price') border-red-400 @enderror"
                    >
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">₫</span>
                </div>
                @error('base_price')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Product Variants Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-700">Biến thể sản phẩm <span class="text-red-500">*</span></h3>
                <button type="button" onclick="addVariantRow()" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Thêm biến thể
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                        <tr>
                            <th class="pb-3 pr-4">Kích cỡ</th>
                            <th class="pb-3 pr-4">Giá bán (₫)</th>
                            <th class="pb-3 pr-4">Kho hàng</th>
                            <th class="pb-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="variant-container">
                        <tr class="variant-row border-t border-gray-50">
                            <td class="py-4 pr-4">
                                <select name="variants[0][size_id]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white" required>
                                    <option value="">Chọn danh mục trước</option>
                                </select>
                            </td>
                            <td class="py-4 pr-4">
                                <input type="number" name="variants[0][price]" placeholder="0" min="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                            </td>
                            <td class="py-4 pr-4">
                                <input type="number" name="variants[0][stock_quantity]" placeholder="0" min="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                            </td>
                            <td class="py-4 text-right">
                                <button type="button" onclick="removeVariantRow(this)" class="text-red-400 hover:text-red-600 disabled:opacity-30 remove-btn" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @error('variants')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Slug Field (Hidden or Read-only) --}}
        <div class="hidden">
             <input type="text" id="slug" name="slug" value="{{ old('slug') }}">
        </div>

        {{-- Image & Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="text-base font-semibold text-gray-700 pb-3 border-b border-gray-100">Hình ảnh & Trạng thái</h3>

            {{-- Image Upload --}}
            <div class="space-y-5">
                {{-- Main Image --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">Ảnh đại diện (Chính)</label>
                    <div class="flex items-start gap-4">
                        <div id="image-preview-wrapper" class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center bg-gray-50 flex-shrink-0 overflow-hidden">
                            <svg id="image-placeholder" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <img id="image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        </div>
                        <div class="flex-1">
                            <label for="image"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Chọn ảnh chính
                            </label>
                            <input type="file" id="image" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                            <p class="text-xs text-gray-400 mt-2">Dung lượng tối đa 10MB. Ảnh này dùng làm ảnh đại diện sản phẩm.</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                {{-- Gallery Images --}}
                <div>
                    <label for="gallery" class="block text-sm font-medium text-gray-700 mb-1.5">Bộ sưu tập ảnh (Ảnh chi tiết - Tối đa 5 ảnh)</label>
                    <div class="space-y-4">
                        <label for="gallery"
                            class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 bg-white hover:bg-gray-50 rounded-xl transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Chọn nhiều ảnh chi tiết
                        </label>
                        <input type="file" id="gallery" name="gallery[]" class="hidden" accept="image/*" multiple onchange="previewGallery(event)">

                        {{-- Gallery Preview Grid --}}
                        <div id="gallery-preview" class="grid grid-cols-5 gap-3">
                            {{-- Placeholder squares --}}
                            @for ($i = 0; $i < 5; $i++)
                                <div class="aspect-square rounded-xl border border-gray-100 bg-gray-50 flex items-center justify-center text-gray-200">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400">Chọn tối đa 5 ảnh chi tiết. Tối đa 10MB/mỗi ảnh.</p>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-400">
                        <span class="text-sm text-gray-700">Đang bán</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-400">
                        <span class="text-sm text-gray-700">Ẩn</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pb-6">
            <a href="{{ route('admin.products.index') }}"
                class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors shadow-sm">
                Huỷ bỏ
            </a>
            <button type="submit"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md hover:shadow-lg">
                Lưu sản phẩm
            </button>
        </div>
    </form>
</div>

<script>
    
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function () {
        const slug = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Variant Management
    let variantIndex = 1; // Khởi tạo index cho các dòng tiếp theo

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
                    <select name="variants[${variantIndex}][size_id]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white" required>
                        ${sizeOptions}
                    </select>
                </td>
                <td class="py-4 pr-4">
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="0" min="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 variant-price" required>
                </td>
                <td class="py-4 pr-4">
                    <input type="number" name="variants[${variantIndex}][stock_quantity]" placeholder="0" min="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 variant-stock" required>
                </td>
                <td class="py-4 text-right">
                    <button type="button" onclick="removeVariantRow(this)" class="text-red-400 hover:text-red-600 remove-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </td>
            </tr>
        `;
        
        container.insertAdjacentHTML('beforeend', newRowHTML);
        variantIndex++;
        updateRemoveButtons();
    }

    // Category-Size Sync Logic
    window.currentSizes = [];
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

            if (hasData && !confirm('Thay đổi danh mục sẽ xóa toàn bộ thông tin biến thể hiện tại. Bạn có chắc chắn?')) {
                this.value = this.dataset.oldValue || "";
                return;
            }

            this.dataset.oldValue = categoryId;

            if (!categoryId) {
                window.currentSizes = [];
                document.getElementById('variant-container').innerHTML = `
                    <tr class="variant-row border-t border-gray-50">
                        <td class="py-4 pr-4"><select name="variants[0][size_id]" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white" required><option value="">Chọn danh mục trước</option></select></td>
                        <td class="py-4 pr-4"><input type="number" name="variants[0][price]" placeholder="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg variant-price" required></td>
                        <td class="py-4 pr-4"><input type="number" name="variants[0][stock_quantity]" placeholder="0" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg variant-stock" required></td>
                        <td class="py-4 text-right"><button type="button" disabled class="text-gray-300"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button></td>
                    </tr>`;
                return;
            }

            // Fetch sizes via AJAX
            const url = `{{ url('admin/get-sizes-by-category') }}/${categoryId}`;
            console.log('Fetching sizes from:', url);

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(sizes => {
                    console.log('Received sizes:', sizes);
                    window.currentSizes = sizes;
                    
                    const container = document.getElementById('variant-container');
                    container.innerHTML = ''; 
                    variantIndex = 0;
                    addVariantRow();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Không thể lấy danh sách kích cỡ. Lỗi: ' + error.message);
                });
        } catch (e) {
            console.error('Lỗi khi thay đổi danh mục:', e);
            alert('Có lỗi xảy ra: ' + e.message);
        }
    });

    function removeVariantRow(btn) {
        btn.closest('tr').remove();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.variant-row');
        const buttons = document.querySelectorAll('.remove-btn');
        buttons.forEach(btn => btn.disabled = rows.length === 1);
    }

    // Image preview
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('image-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    // Gallery preview
    function previewGallery(event) {
        const files = event.target.files;
        const previewGrid = document.getElementById('gallery-preview');
        previewGrid.innerHTML = ''; // Clear existing

        // Limit to 5
        const fileCount = Math.min(files.length, 5);

        for (let i = 0; i < fileCount; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            const wrapper = document.createElement('div');
            wrapper.className = 'aspect-square rounded-xl border border-gray-100 overflow-hidden bg-gray-50';
            
            reader.onload = function (e) {
                wrapper.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
            previewGrid.appendChild(wrapper);
        }

        // Add placeholders if less than 5
        for (let i = fileCount; i < 5; i++) {
            const placeholder = document.createElement('div');
            placeholder.className = 'aspect-square rounded-xl border border-gray-100 bg-gray-50 flex items-center justify-center text-gray-200';
            placeholder.innerHTML = `
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>`;
            previewGrid.appendChild(placeholder);
        }
    }
</script>
@endsection
