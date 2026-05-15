<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');

        // 🔍 Search by order ID or customer name
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', $searchTerm)
                    ->orWhereHas('customer', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // ✅ Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ Filter by date
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        $filteredQuery = clone $query;

        $statusCounts = (clone $filteredQuery)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalRevenue = (clone $filteredQuery)->sum('total_price');
        $totalOrders = (clone $filteredQuery)->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $orders = $filteredQuery->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact(
            'orders',
            'statusCounts',
            'totalRevenue',
            'averageOrderValue',
            'totalOrders'
        ));
    }



    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'address']);
        return view('admin.orders.show', compact('order'));
    }


    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function invoice(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function downloadInvoicePDF(Order $order)
    {
        $order->load(['customer', 'items.product']);

        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}
