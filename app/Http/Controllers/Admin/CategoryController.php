<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Sizes;
use Illuminate\Support\Str;
use App\Models\ProductVariants;


class CategoryController extends Controller
{
    public function index()
    {
        $products = Products::all();
        $categories = Categories::all();
        $sizes = Sizes::all(); // Thêm dòng này để lấy tất cả size

        $categorywithproduct = Categories::whereHas('products')->get();
        $categorywithoutproduct = Categories::whereDoesntHave('products')->get();

        return view('admin.category.index', compact('categories', 'products', 'categorywithproduct', 'categorywithoutproduct', 'sizes'));
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validateWithBag('category_create', [
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

    public function show(Categories $category)
    {
        $category = Categories::with('sizes')->find($category->id);
        $sizes = Sizes::all();
        return view('admin.category.detail', compact('category', 'sizes'));
    }

    public function update(Request $request, Categories $category){
        $request->validateWithBag('category_update_' . $category->id, [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:product_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
        ]);

        // Kiểm tra xem có kích thước nào bị xóa mà đang có sản phẩm sử dụng không
        $currentSizeIds = $category->sizes()->pluck('sizes.id')->toArray();
        $newSizeIds = $request->sizes;
        $removedSizeIds = array_diff($currentSizeIds, $newSizeIds);

        if (!empty($removedSizeIds)) {
            $isUsed = ProductVariants::whereIn('size_id', $removedSizeIds)
                ->whereHas('product', function($query) use ($category) {
                    $query->where('category_id', $category->id);
                })->exists();

            if ($isUsed) {
                return redirect()->back()
                ->with('error', 
                'Một số kích thước đang được sử dụng bởi sản phẩm trong danh mục này nên không thể thay đổi!');
            }
        }

        $category->update([
            'name' => $request->name,       
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        if ($request->has('sizes')) {
            $category->sizes()->sync($request->sizes);
        }

        return redirect()->back()->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Categories $category){
        
        if($category->products()->count() >0){
            return redirect()->back()->with('error', 'Danh mục đã có sản phẩm nên không thể xóa!');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Xóa danh mục thành công!');
    }
    
}
