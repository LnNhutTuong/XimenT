<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Brands;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Products::count();
        $categoryCount = Categories::count();
        $brandCount = Brands::count();

        
        return view('admin.dashboard.index', compact('productCount', 'categoryCount', 'brandCount'));
    }

    public function store(){
        
    }
}
