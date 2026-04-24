<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Brands;

use Illuminate\Support\Facades\DB;
use App\Models\Orders;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Products::count();
        $categoryCount = Categories::count();
        $brandCount = Brands::count();

        $currentYear = date('Y');

        // Doanh thu theo tháng
        $revenueData = Orders::select(
                DB::raw('MONTH(order_date) as month'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereYear('order_date', $currentYear)
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_revenue', 'month')
            ->toArray();

        //So luong ban ra
        $salesData = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select(
                DB::raw('MONTH(orders.order_date) as month'),
                DB::raw('SUM(order_details.quantity) as total_quantity')
            )
            ->whereYear('orders.order_date', $currentYear)
            ->where('orders.status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_quantity', 'month')
            ->toArray();

        // Điền dữ liệu cho 12 tháng
        $monthlyRevenue = [];
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = $revenueData[$i] ?? 0;
            $monthlySales[] = $salesData[$i] ?? 0;
        }

        return view('admin.dashboard.index', compact(
            'productCount', 'categoryCount', 'brandCount',
            'monthlyRevenue', 'monthlySales', 'currentYear'
        ));
    }

    public function store(){
        
    }
}
