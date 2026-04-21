<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with(['items.variant.product', 'items.variant.size'])->first();
        $cartItems = $cart ? $cart->items : collect();
        $total = $cartItems->sum(fn($item) => ($item->variant->discount_price ?? $item->variant->price) * $item->quantity);
        
        $customer = Customer::where('user_id', $userId)->first();

        return view('frontend.cart.index', compact('cartItems', 'total', 'customer'));
    }

    public function add(Request $request){
        $request->validate([
            'variant_id'=>'required|exists:product_variants,id',
            'quantity'=>'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $variantId = $request->input("variant_id");
        $quantity = $request->input("quantity");

        $cart = Cart::firstOrCreate([
            'user_id' => $userId
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('product_variant_id', $variantId)
        ->first();

        if($cartItem){
            $cartItem->quantity += $quantity;
            $cartItem->save();
        }else{
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variantId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');
    }

    public function destroy(CartItem $cartItem){
        $cartItem->delete();
        return redirect()->back()->with('success', 'Đã xóa sản phẩm giỏ hàng !');
    }
    
}
