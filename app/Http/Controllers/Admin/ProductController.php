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
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with('variants')->get();
        $categories = Categories::with('sizes')->get();
        $brands = Brands::all();
        $productsInStock = Products::where('is_active', 1)->get();
        $productsOutOfStock = Products::where('is_active', 0)->get();

        $ProductVariants = ProductVariants::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'productsInStock', 'productsOutOfStock', 'ProductVariants'));
    }

   public function store(Request $request)
    {
        $request->merge([
            'base_price'       => (int) preg_replace('/[^0-9]/', '', $request->base_price),
            'sell_price'       => (int) preg_replace('/[^0-9]/', '', $request->sell_price),
            'discount_percent' => (int) preg_replace('/[^0-9]/', '', $request->discount_percent),
        ]);

        $request->validateWithBag('product_create', [
            // product
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
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
            'discount_percent' => 'nullable|numeric', 
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Products::create([
            'category_id' => $request->product_category_id,
            'brand_id' => $request->product_brand_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name), 
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
                        'discount_price' => $request->discount_percent, 
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

        return redirect()->route('admin.products.index')->with("success", "Thêm sản phẩm thành công!");   
    }
}
