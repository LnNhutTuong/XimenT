<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Customer;
use App\Models\Products;
use App\Models\ProductVariants;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Orders::with(['customer', 'details.variant.product', 'details.variant.size'])
                    ->when($request->search, function ($query, $search) {
                        $query->where('order_code', 'like', "%{$search}%")
                              ->orWhereHas('customer', function($q) use ($search) {
                                  $q->where('name', 'like', "%{$search}%");
                              });
                    })
                    ->when($request->status, function ($query, $status) {
                        $query->where('status', $status);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(5)
                    ->withQueryString();
                    
        $orderItems = OrderDetails::with('order', 'variant')->get();
        $customers = Customer::with('user')
                    ->whereNotNull('user_id')
                    ->whereHas('user', function ($query) {
                        $query->where('role', 'user')
                            ->where('status', 1);
                    })
                    ->get();
                    
        $products = Products::where('is_active', 1)->with('variants.size')->get();
        return view('admin.order.index', compact('orders', 'orderItems', 'customers', 'products'));
    }

    public function store(Request $request){

        $request->validate([
            'choose-type-customers' => 'required',
            'user-id' => 'required_if:choose-type-customers,user',
            'guest-name' => 'required_if:choose-type-customers,guest',
            'phone' => 'required|numeric|digits:10',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'payment_method' => 'required|in:cod,vnpay',

            'items' => 'required|array',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try { DB::transaction(function() use ($request) {
            $customerId = null;
            $userId = null;
            
            if($request->input('choose-type-customers') === 'user'){
                 $customer = Customer::findOrFail($request->input('user-id'));
                 $customerId = $customer->id;
                 $userId = $customer->user_id;
            }else{
             $customer = Customer::firstOrCreate(
                    ['phone' => $request->phone],
                    [
                        'name' => $request->input('guest-name'),
                        'address' => $request->address,
                        'note' => $request->note,
                    ]
                );
                $customerId = $customer->id;
            }
            $orderCode = 'XMT' . now()->format('ymd') . strtoupper(\Illuminate\Support\Str::random(5));

            $order = Orders::create([
                'order_code' => $orderCode,
                'customer_id' => $customerId,
                'user_id' => $userId,
                'total_amount' => 0,
                'status' => 'pending',
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'note' => $request->input('note'),
                'payment_method' => $request->input('payment_method'),
                'order_date' => now()->timezone('Asia/Ho_Chi_Minh'),
            ]);
            
            $total_amount =0;
            foreach($request->items as $item){
                $variant = ProductVariants::findOrFail($item['variant_id']);
                
                if($variant->stock_quantity < $item['quantity']){
                    throw new \Exception("Sản phẩm {$variant->product->name} không đủ!");
                }

                $price = $variant->discount_price ?? $variant->price;
                $total_amount += $price * $item['quantity'];

                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);
                
                //tru kho theo kieu Eloquent
                $variant->decrement('stock_quantity', $item['quantity']);
                if($variant->stock_quantity == 0){
                    $variant->product->update(['is_active' => 0]);
                }
            }
            $order->update(['total_amount' => $total_amount]);
        });

        return redirect()->back()->with('success', 'Thêm đơn hàng thành công');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Thêm đơn hàng thất bại: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Orders $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled,return',
        ]);

        try {

            if (in_array($order->status, ['completed', 'cancelled', 'return'])) {
                return redirect()->back()->with('error', 'Đơn hàng đã kết thúc, không thể cập nhật.');
            }
            
            DB::transaction(function () use ($request, $order) {

                $oldStatus = $order->status;
                $newStatus = $request->status;

                $order->update([
                    'status' => $newStatus,
                    'updated_at' => now()->timezone('Asia/Ho_Chi_Minh'),
                ]);

                $restockStatuses = ['cancelled', 'return']; // những trạng thái khiến cho product +1 stock lại

                if (
                    !in_array($oldStatus, $restockStatuses) &&
                    in_array($newStatus, $restockStatuses)
                ) {

                    foreach ($order->details as $detail) {
                        $variant = ProductVariants::findOrFail($detail->product_variant_id);
                        $variant->increment('stock_quantity', $detail->quantity);
                        if ($variant->stock_quantity > 0) {
                            $variant->product->update([
                                'is_active' => 1
                            ]);
                        }
                    }
                }
            });

            return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Cập nhật đơn hàng thất bại: ' . $e->getMessage());
        }
    }

    public function destroy(Orders $order){

            if (in_array($order->status, ['completed', 'cancelled', 'return'])) {
                $order->details()->delete();
                $order->delete();
                return redirect()->back()->with('success', 'Xóa đơn hàng thành công');
            }

            return redirect()->back()->with('error','Không thể xóa các đơn hàng đang trong quá trình xử lí');
            
    }
  
}
