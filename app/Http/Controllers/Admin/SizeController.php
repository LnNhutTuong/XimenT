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
}
