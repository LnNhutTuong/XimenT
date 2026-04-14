<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\ProductVariants;
use App\Models\Sizes;

class ProductController extends Controller
{
       public function index()
    {
        $products = Products::all();
        $categories = Categories::all();
        $brands = Brands::all();
        $sizes = Sizes::all();
        $productsInStock = Products::where('is_active', 1)->get();
        $productsOutOfStock = Products::where('is_active', 0)->get();

        $ProductVariants = ProductVariants::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'productsInStock', 'productsOutOfStock', 'ProductVariants', 'sizes'));
    }
}
