@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm - XimenT Admin')

@section('content')
<div>
    <div id="modal">
        <div
                 class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] overflow-auto">
                    <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6 relative">
                        <div class="flex items-center pb-3 border-b border-gray-300">
                            <h3 class="text-slate-900 text-xl font-semibold flex-1">Modal Title</h3>
                            <svg id="closeIcon" xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5 ml-2 cursor-pointer shrink-0 fill-gray-400 hover:fill-red-500"
                                viewBox="0 0 320.591 320.591">
                                <path
                                    d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                                    data-original="#000000"></path>
                                <path
                                    d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                                    data-original="#000000"></path>
                            </svg>
                        </div>

                        <div class="my-6">
                            <p class="text-slate-600 text-sm leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing
                                elit. Sed auctor auctor arcu,
                                at fermentum dui. Maecenas Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor
                                auctor arcu,
                                at fermentum dui. Maecenas.</p>
                            <p class="text-slate-600 text-sm leading-relaxed mt-2">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Sed auctor auctor arcu,
                                at fermentum dui. Maecenas Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor
                                auctor arcu,
                                at fermentum dui. Maecenas.</p>
                            <p class="text-slate-600 text-sm leading-relaxed mt-2">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Sed auctor auctor arcu,
                                at fermentum dui. Maecenas Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor
                                auctor arcu,
                                at fermentum dui. Maecenas.</p>
                        </div>

                        <div class="border-t border-gray-300 pt-6 flex justify-end gap-4">
                            <button id="closeButton" type="button" class="px-4 py-2 cursor-pointer rounded-lg text-slate-900 text-sm font-medium border-none outline-none tracking-wide bg-gray-200 hover:bg-gray-300 active:bg-gray-200">Close</button>
                            <button type="button" class="px-4 py-2 cursor-pointer rounded-lg text-white text-sm font-medium border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600">Save</button>
                        </div>
                    </div>
                </div>
    </div>

     <button id="openModal" type="button" class="mt-4 cursor-pointer mx-auto block px-4 py-2 rounded-lg text-white text-sm font-medium border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600">Open modal</button>
</div>


<script>
    let variantIndex = {{ $product->variants->count() }};
    window.currentSizes = [];

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

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.variant-row');
        const buttons = document.querySelectorAll('.remove-btn');
        buttons.forEach(btn => btn.disabled = rows.length === 1);
    }
</script>
@endsection
