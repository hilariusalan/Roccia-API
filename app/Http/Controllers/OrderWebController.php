<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;

class OrderWebController extends Controller
{
    public function getOrders(Request $request) {
        $statuses = Status::all();

        $date = $request->query('date');
        $statusId = $request->query('status_id');

        $orders = Order::with(['users', 'statuses']);

        if ($date) {
            $orders->whereDate('created_at', $date);
        }

        if ($statusId) {
            $orders->where('status_id', $statusId);
        }

        return view('orders.components.list-orders', [
            'orders' => $orders->get(),
            'statuses' => $statuses,
            'filters' => [
                'date' => $date,
                'status_id' => $statusId
            ]
        ]);
    }
}
