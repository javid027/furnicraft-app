<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalRevenue = Order::sum('total_price');

        $latestOrders = Order::with('customer')->latest()->take(5)->get();

        $activityFeed = Order::with('customer')->latest()->take(7)->get();

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as line_revenue'),
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        // Monthly Data (last 12 months)
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('count', 'month');

        $monthlyCustomers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('count', 'month');

        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        // Prepare month labels and fill gaps
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('M');
        });

        $orderData = $months->map(function ($_, $index) use ($monthlyOrders) {
            return $monthlyOrders->get($index + 1, 0);
        });

        $customerData = $months->map(function ($_, $index) use ($monthlyCustomers) {
            return $monthlyCustomers->get($index + 1, 0);
        });

        $revenueData = $months->map(function ($_, $index) use ($monthlyRevenue) {
            return (float) $monthlyRevenue->get($index + 1, 0);
        });

        $monthlyProducts = Product::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('count', 'month');

        $productData = $months->map(function ($_, $index) use ($monthlyProducts) {
            return $monthlyProducts->get($index + 1, 0);
        });

        $now = now();
        $prev = $now->copy()->subMonth();

        $ordersThisMonth = Order::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $ordersPrevMonth = Order::whereYear('created_at', $prev->year)
            ->whereMonth('created_at', $prev->month)
            ->count();

        $customersThisMonth = Customer::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $customersPrevMonth = Customer::whereYear('created_at', $prev->year)
            ->whereMonth('created_at', $prev->month)
            ->count();

        $revenueThisMonth = (float) Order::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_price');
        $revenuePrevMonth = (float) Order::whereYear('created_at', $prev->year)
            ->whereMonth('created_at', $prev->month)
            ->sum('total_price');

        $pct = function (float|int $current, float|int $previous): float {
            if ($previous > 0) {
                return round((($current - $previous) / $previous) * 100, 1);
            }

            return $current > 0 ? 100.0 : 0.0;
        };

        $productsThisMonth = Product::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $productsPrevMonth = Product::whereYear('created_at', $prev->year)
            ->whereMonth('created_at', $prev->month)
            ->count();

        $trends = [
            'orders' => $pct($ordersThisMonth, $ordersPrevMonth),
            'customers' => $pct($customersThisMonth, $customersPrevMonth),
            'revenue' => $pct($revenueThisMonth, $revenuePrevMonth),
            'products' => $pct($productsThisMonth, $productsPrevMonth),
        ];

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'totalRevenue',
            'latestOrders',
            'activityFeed',
            'topProducts',
            'months',
            'orderData',
            'customerData',
            'revenueData',
            'productData',
            'trends',
        ));
    }
}
