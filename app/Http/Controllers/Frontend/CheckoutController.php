<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\ProductVariants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|string|in:cod,vnpay',
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        try {
            DB::beginTransaction();

            $customer = Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );

            $total = 0;
            $cartItems = $cart->items()->with('variant')->get();
            foreach ($cartItems as $item) {
                $price = $item->variant->discount_price > 0 ? $item->variant->discount_price : $item->variant->price;
                $total += $price * $item->quantity;
            }

            $shippingFee = $total >= 2000000 ? 0 : 30000;
            $finalTotal = $total + $shippingFee;

            $orderCode = 'XMT' . now()->format('ymd') . strtoupper(Str::random(5));

            // 4. Tạo đơn hàng
            $order = Orders::create([
                'order_code' => $orderCode,
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'total_amount' => $finalTotal,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note,
                'order_date' => now(),
            ]);

            foreach ($cartItems as $item) {
                $price = $item->variant->discount_price > 0 ? $item->variant->discount_price : $item->variant->price;
                
                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ]);

                $variant = $item->variant;
                if ($variant->stock_quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$variant->product->name} (Size {$variant->size->name}) không đủ số lượng trong kho.");
                }
                $variant->decrement('stock_quantity', $item->quantity);
            }

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('cart')->with([
                'order_success' => true,
                'order_code' => $orderCode,
                'phone' => $request->phone,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
