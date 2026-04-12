<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Sizes;
use Illuminate\Support\Str;



class CategoryController extends Controller
{
    public function index()
    {
        $products = Products::all();
        $categories = Categories::all();
        $sizes = Sizes::all(); // Thêm dòng này để lấy tất cả size

        $categorywithproduct = Categories::whereHas('products')->get();
        $categorywithoutproduct = Categories::whereDoesntHave('products')->get();

        return view('admin.categories.index', compact('categories', 'products', 'categorywithproduct', 'categorywithoutproduct', 'sizes'));
    }

    public function create(){
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_categories,slug',
            'description' => 'nullable|string',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
        ]);

        $category = Categories::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        if ($request->has('sizes')) {
            $category->sizes()->sync($request->sizes);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }
    
}
