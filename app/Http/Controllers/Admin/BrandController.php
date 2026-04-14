<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function store(Request $request){

        $request->validateWithBag('brand_create', [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_brands,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;  
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('brands', 'public');
        }

        $brand = Brands::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'logo' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Thêm thương hiệu thành công!');
    }

    public function update(Request $request, Brands $brand){

        $request->validateWithBag('brand_update', [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_brands,slug,'.$brand->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = $brand->logo;
        if($request->hasFile('image')){
            if($brand->logo){
                Storage::disk('public')->delete($brand->logo); // Xóa hình cũ
            }
            $imagePath = $request->file('image')->store('brands', 'public');
        }

        $brand->update([
            'name'=>$request->name,
            'slug'=>$request->slug,
            'logo'=>$imagePath,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy(Brands $brand){
        if($brand->logo){
            Storage::disk('public')->delete($brand->logo);
        }
        $brand->delete();
        return redirect()->back()->with('success', 'Xóa thương hiệu thành công!');
    }
}
