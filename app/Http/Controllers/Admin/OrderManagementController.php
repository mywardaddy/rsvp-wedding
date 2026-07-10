<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function index(Request $request)
    {
        $query = Order::with('pricingPackage')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('groom_name', 'like', "%{$search}%")
                    ->orWhere('bride_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending_payment')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['pricingPackage', 'payments.transactions']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:paid,cancelled'],
        ]);

        if ($request->status === 'paid') {
            $this->orderService->markAsPaid($order);
            return back()->with('success', 'Pesanan ' . $order->order_number . ' telah ditandai sebagai Lunas.');
        }

        if ($request->status === 'cancelled') {
            $this->orderService->cancelOrder($order);
            return back()->with('success', 'Pesanan ' . $order->order_number . ' telah dibatalkan.');
        }

        return back();
    }
}
