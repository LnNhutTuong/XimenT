<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\ProductVariants;
use App\Models\Sizes;
use App\Models\ProductImages;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::with('category')->get(); // Lấy sản phẩm kèm tên danh mục
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        $brands = Brands::all();
        $sizes = Sizes::all();
        return view('admin.products.create', compact('categories', 'brands', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'nullable|exists:product_brands,id',
            'base_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'is_active' => 'required|in:0,1',
            
            // Validation cho Biến thể
            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',

            // Validation cho Album ảnh
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
        ], [
            'variants.required' => 'Bạn phải nhập ít nhất 1 biến thể sản phẩm.',
            'gallery.max' => 'Bạn chỉ có thể upload tối đa 5 ảnh phụ.',
            'image.max' => 'Ảnh đại diện không được vượt quá 10MB.',
            'gallery.*.max' => 'Mỗi ảnh chi tiết không được vượt quá 10MB.',
        ]);

        try {
            \DB::beginTransaction();

            $data = $request->all();

            // 2. Xử lý ảnh đại diện
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            // 3. Lưu Sản phẩm
            $product = Products::create($data);

            // 4. Lưu Bộ sưu tập ảnh (Gallery)
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $imageFile) {
                    $path = $imageFile->store('products/gallery', 'public');
                    ProductImages::create([
                        'product_id' => $product->id,
                        'image_path' => $path
                    ]);
                }
            }

            // 5. Lưu danh sách Biến thể
            foreach ($request->variants as $variantData) {
                $size = Sizes::find($variantData['size_id']);
                
                // Sinh mã SKU tự động: slug-sizename
                $variantData['sku'] = $product->slug . '-' . \Str::slug($size->name);
                $variantData['product_id'] = $product->id;

                ProductVariants::create($variantData);
            }

            \DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm và album ảnh thành công!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::with(['images', 'variants', 'category.sizes'])->findOrFail($id);
        $categories = Categories::all();
        $brands = Brands::all();
        $sizes = Sizes::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Products::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug,' . $id,
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'nullable|exists:product_brands,id',
            'base_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'is_active' => 'required|in:0,1',
            // Biến thể
            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            // Ảnh phụ mới
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        try {
            \DB::beginTransaction();

            $data = $request->all();

            // 1. Cập nhật ảnh đại diện nếu có
            if ($request->hasFile('image')) {
                if ($product->image && \Storage::disk('public')->exists($product->image)) {
                    \Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            // 2. Thêm ảnh phụ mới
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $imageFile) {
                    $path = $imageFile->store('products/gallery', 'public');
                    ProductImages::create([
                        'product_id' => $product->id,
                        'image_path' => $path
                    ]);
                }
            }

            // 3. Cập nhật Biến thể (Cách đơn giản: Xóa cũ tạo mới)
            $product->variants()->delete();
            foreach ($request->variants as $variantData) {
                $size = Sizes::find($variantData['size_id']);
                $variantData['sku'] = $product->slug . '-' . \Str::slug($size->name);
                $variantData['product_id'] = $product->id;
                ProductVariants::create($variantData);
            }

            \DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Products::findOrFail($id);
        
        // Xóa ảnh chính
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        // Xóa tất cả ảnh phụ
        foreach ($product->images as $img) {
            if (\Storage::disk('public')->exists($img->image_path)) {
                \Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    /**
     * Xóa riêng lẻ 1 ảnh phụ (AJAX)
     */
    public function deleteImage($id)
    {
        $image = ProductImages::findOrFail($id);
        if (\Storage::disk('public')->exists($image->image_path)) {
            \Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        return response()->json(['success' => true]);
    }
    /**
     * Lấy danh sách Size dựa trên Category (Dùng cho AJAX)
     */
    public function getSizesByCategory($id)
    {
        $category = Categories::with('sizes')->findOrFail($id);
        return response()->json($category->sizes);
    }
}
