<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sizes;

class SizeController extends Controller
{
    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name'
        ]);

        $size = Sizes::create([
            'name'=>$request->name
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm kích cỡ thành công!',
                'data' => $size
            ]);
        }

        return redirect()->back()->with("success","Thêm thành công!");
    }

    public function destroy($id)
    {
        $size = Sizes::findOrFail($id);

        if ($size->variants()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa kích cỡ này vì có sản phẩm đang sử dụng!');
        }

        $size->delete();

        return redirect()->back()->with('success', 'Xóa kích cỡ thành công!');
    }

}
