<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brands;
use App\Models\Products;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brands::all();
        $products = Products::all();
        $brandwithproduct = Brands::has('products')->get();
        $brandwithoutproduct = Brands::doesntHave('products')->get();
        return view('admin.brand.index', compact('brands', 'products', 'brandwithproduct', 'brandwithoutproduct'));
    }
}
