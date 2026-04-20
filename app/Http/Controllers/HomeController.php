<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\Products;
use App\Models\ProductVariants;

class HomeController extends Controller
{
    public function index(){
        $brands = Brands::all();
        $products = Products::all();
        return view('frontend.layouts.app', compact('brands', 'products')); 
    }

    public function products(){
        $categories = Categories::all();
        $brands = Brands::all();
        $products = Products::with("variants.size")->get();
        $productVariants = ProductVariants::all();
        return view('frontend.products.index', compact('categories', 'brands', 'products', 'productVariants'));
    }

    public function productDetail($slug){
        return view('frontend.products.show'); 
    }
}
