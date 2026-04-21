<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\ProductVariants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderHistoryController extends Controller
{

    public function index()
    {
        $userId = Auth::id();
        
        $orders = Orders::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    public function cancel(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng đang chờ duyệt.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'cancelled',
                    'updated_at' => now()->timezone('Asia/Ho_Chi_Minh'),
                ]);

                // 4. Hoàn lại tồn kho
                foreach ($order->details as $detail) {
                    $variant = ProductVariants::findOrFail($detail->product_variant_id);
                    $variant->increment('stock_quantity', $detail->quantity);
                    
                    if ($variant->stock_quantity > 0) {
                        $variant->product->update(['is_active' => 1]);
                    }
                }
            });

            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng.');
        }
    }
}
