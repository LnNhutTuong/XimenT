<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\Products;
use App\Models\ProductVariants;
use App\Models\ProductImages;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $brands = Brands::all();
        $list_products = Products::with('variants')->where('is_active', 1)->latest()->take(6)->get();
        return view('frontend.home.index', compact('brands', 'list_products')); 
    }

    public function products(){
        $categories = Categories::all();
        $brands = Brands::all();
        $products = Products::with("variants.size")->with('images')->where('is_active', 1)->get();
        $productVariants = ProductVariants::all();
        return view('frontend.products.index', compact('categories', 'brands', 'products', 'productVariants'));
    }

    public function filter_product(Request $request)
    {
        $query = Products::with("variants.size");

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->sort == 'price_asc') {
            $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->orderBy('product_variants.price', 'asc')
                ->select('products.*')->distinct();
        } elseif ($request->sort == 'name_asc') {
            $query->orderBy('name', 'asc');
        }

        $products = $query->get();
        $categories = Categories::all();
        $brands = Brands::all();

        return view('frontend.products.index', compact('categories', 'brands', 'products'));
    }

    public function productDetail($slug){
        $product = Products::where('slug', $slug)->firstOrFail();
        $productVariants = ProductVariants::where('product_id', $product->id)->get();
        $productImages = ProductImages::where('product_id', $product->id)->get();
        return view('frontend.products.detail', compact('product', 'productVariants', 'productImages')); 
    }

    public function brands(){
        $brands = Brands::with(['products.variants.size'])->where('is_active', 1)->get();
        $categories = Categories::all();
        return view('frontend.brands.index', compact('brands', 'categories'));
    }


}
