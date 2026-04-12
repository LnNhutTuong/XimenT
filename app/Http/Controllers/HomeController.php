<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        return view('frontend.layouts.app'); 
    }

    public function products(){
        $products = DB::table('products')->get();

        return view('frontend.products.index', compact('products'));
    }

    public function productDetail($slug){
        return view('frontend.products.show'); 
    }
}
