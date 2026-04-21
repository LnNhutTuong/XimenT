<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\ProductVariants;
use App\Models\Sizes;
use App\Models\ProductImages;
use App\Models\Orders;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with(['variants','images'])->get();
        $categories = Categories::with('sizes')->get();
        $brands = Brands::all();
        $productsInStock = Products::where('is_active', 1)->get();
        $productsOutOfStock = Products::where('is_active', 0)->get();

        $ProductVariants = ProductVariants::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'productsInStock', 'productsOutOfStock', 'ProductVariants'));
    }

   public function store(Request $request)
   {
        $discountValue = preg_replace('/[^0-9]/', '', $request->discount_amount);
        
        $request->merge([
            'base_price'      => (int) preg_replace('/[^0-9]/', '', $request->base_price),
            'sell_price'      => (int) preg_replace('/[^0-9]/', '', $request->sell_price),
            // Nếu trống thì để null, nếu có số thì mới ép kiểu int
            'discount_amount' => $discountValue !== '' ? (int) $discountValue : null,
        ]);

        //random slug or sku (giải quyết được cái function 300 dòng của tôi :D)
        $baseSlug = $request->slug; //gửi lên
        $slug = $baseSlug; // lưu mẫu
        
        $count = 1; 
        while (Products::where('slug', $slug)->exists()) { // nếu tồn tại
            $slug = $baseSlug . '-' . $count; // nối chuỗi count vào
            $count++; // nếu trùng thì lên nữa
        }

        //nếu tạo slug kiểu trên bắt buộc phải bỏ validate vì nếu validate thì nó sẽ có unique :D
        // mà nếu unique thì nó sẽ không cho tạo slug trùng nhau :D 

        $request->validateWithBag('product_create', [
            // product
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_brand_id' => 'required|exists:product_brands,id',
            'product_status' => 'required|boolean', 
            'base_price' => 'required|numeric',
            'sell_price' => 'required|numeric', 
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // gallery images
            'gallery_images' => 'nullable|array', 
            'gallery_images.*' => 'image|max:2048',

            // variants
            'sizes' => 'required|array', 
            'quantities' => 'required|array',
            'discount_amount' => 'nullable|numeric', 
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Products::create([
            'category_id' => $request->product_category_id,
            'brand_id' => $request->product_brand_id,
            'name' => $request->name,
            'slug' => $slug, 
            'description' => $request->description,
            'base_price' => $request->base_price,
            'image' => $imagePath,
            'is_active' => $request->product_status,
        ]);

        if ($request->has('sizes') && $request->has('quantities')) {
            foreach ($request->sizes as $index => $size_id) {
                $quantity = $request->quantities[$index] ?? 0;

                if (!empty($size_id)) {
                    ProductVariants::create([
                        'product_id' => $product->id, 
                        'size_id' => $size_id,
                        'stock_quantity' => $quantity,
                        'price' => $request->sell_price,
                        'discount_price' => $request->discount_amount, 
                        'sku' => Str::upper(Str::random(8)),
                    ]);
                }
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('products/gallery', 'public');

                ProductImages::create([
                    'product_id' => $product->id, 
                    'image_path' => $path, 
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->back()->with("success", "Thêm sản phẩm thành công!");   
    }

    public function update(Request $request, Products $product){
        $discountValue = preg_replace('/[^0-9]/', '', $request->discount_amount);
        
        $request->merge([
            'base_price'      => (int) preg_replace('/[^0-9]/', '', $request->base_price),
            'sell_price'      => (int) preg_replace('/[^0-9]/', '', $request->sell_price),
            'discount_amount' => $discountValue !== '' ? (int) $discountValue : null,
        ]);

        $slug = $request->slug;

        $request->validateWithBag('product_create', [
            // product
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_brand_id' => 'required|exists:product_brands,id',
            'product_status' => 'required|boolean', 
            'base_price' => 'required|numeric',
            'sell_price' => 'required|numeric', 

            //khác với store thì update không bắt buộc phải có ảnh
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // gallery images
            'gallery_images' => 'nullable|array', 
            'gallery_images.*' => 'image|max:2048',

            // variants
            'sizes' => 'required|array', 
            'quantities' => 'required|array',
            'discount_amount' => 'nullable|numeric', 
        ]);

        $imagePath = $product->image;
        if($request->hasFile('image')){
            if($product->image){
                Storage::disk('public')->delete($product->image); 
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $hasOrder = $product->variants()->whereHas('order_details')->exists();

        $product->update([
            'category_id' => $request->product_category_id,
            'brand_id' => $request->product_brand_id,
            'name' => $request->name,
            'slug' => $slug, 
            'description' => $request->description,
            'base_price' => $request->base_price,
            'image' => $imagePath,
            'is_active' => $hasOrder ? $product->is_active : $request->product_status,
        ]);


        // Xóa các biến thể không còn trong danh sách gửi lên
        $requestedSizeIds = $request->sizes ?? [];
        $existingVariants = $product->variants;

        foreach ($existingVariants as $variant) {
            if (!in_array($variant->size_id, $requestedSizeIds)) {
                $hasActiveOrders = $variant->order_details()
                    ->whereHas('order', function($q) {
                        $q->whereNotIn('status', ['completed', 'cancelled', 'return']);
                    })->exists();

                if ($hasActiveOrders) {
                    $sizeName = $variant->size->name ?? 'không xác định';
                    return redirect()->back()->with('error', "Biến thể kích thước $sizeName đang có trong đơn hàng chưa hoàn tất nên không thể xóa!");
                }
                
                $variant->delete();
            }
        }

        //neu co thi update con khong co thi them
       if ($request->has('sizes')) {
        foreach ($request->sizes as $index => $size_id) {
            if (empty($size_id)) continue;
            ProductVariants::updateOrCreate(
                    ['product_id' => $product->id, 'size_id' => $size_id], 
                    [
                        'stock_quantity' => $request->quantities[$index] ?? 0,
                        'price' => $request->sell_price,
                        'discount_price' => $request->discount_amount,
                        'sku' => Str::upper(Str::random(8)),
                    ]
                );
            }
        }

        //them anh moi
        if ($request->hasFile('gallery_images')) {

            $product->images()->delete();

            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('products/gallery', 'public');

                ProductImages::create([
                    'product_id' => $product->id, 
                    'image_path' => $path, 
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->back()->with("success", "Cập nhật sản phẩm thành công!");   
    }

    public function destroy(Products $product){

        $hasOrder = $product->variants()->whereHas('order_details')->exists();
        if($hasOrder){
            return redirect()->back()->with("error", "Sản phẩm đã có đơn hàng không thể xóa!");   
        }

        $product->variants()->delete();

        if($product->image){
            Storage::disk('public')->delete($product->image);
        }
        if($product->images()->exists()){
            foreach($product->images() as $image){
                Storage::disk('public')->delete($image->image_path);
            }
            $product->images()->delete();
        }

        $product->delete();
        return redirect()->back()->with("success", "Xóa sản phẩm thành công!");   
    }
}

