<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Customer;
use App\Models\Products;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::with('customer')->get();
        $orderItems = OrderDetails::with('order', 'variant')->get();
        $customers = Customer::All();
        $products = Products::All();
        return view('admin.order.index', compact('orders', 'orderItems', 'customers', 'products'));
    }

    public function store(Request $request){
        dd($request->all());

    }
}
