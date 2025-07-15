<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderUserResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getOrders(Request $request): JsonResponse {
        $date = $request->query('date');
        $statusId = $request->query('status_id');
        
        $query = Order::query()
                        ->with([
                            'users',
                            'statuses'
                        ]);

        if($date != null) {
            $query->where('created_at', $date);
        }

        if($statusId != null) {
            $query->where('status_id', $statusId);
        } 

        return response()->json([
            'data' => OrderResource::collection($query)
        ])->setStatusCode(200);
    }

    public function getUserOrders(Request $request): JsonResponse {
        $user = Auth::user();

        $date = $request->query('date');
        $statusId = $request->query('status_id');

        $query = Order::query()
                        ->with([
                            'users',
                            'statuses'
                        ])->where('user_id', $user->id);

        if($date != null) {
            $query->where('created_at', $date);
        }

        if($statusId != null) {
            $query->where('status_id', $statusId);
        } 

        return response()->json([
            'data' => OrderUserResource::collection($query)
        ])->setStatusCode(200);
    }

    public function getOrderDetail(int $orderId): JsonResponse {
        $order = Order::with([
            'users',
            'orderItems',
            'shippingAddresses',
            'billingAddresses',
            'shippingMethods',
            'statuses'
        ])->where('id', $orderId)->first();

        if (!$order) {
            throw new HttpResponseException(response()->json([
                'error' => 'Collection not found.'
            ])->setStatusCode(404));
        }

        $orderItems = OrderItem::with(['productVariants', 'orders'])->where('order_id', $orderId)->get();

        return response()->json([
            'data' => [
                'user' => $order->users->email,
                'shipping' => $order->shippingAddresses->name,
                'billing_address' => $order->billingAddresses->name,
                'shipping_method' => [
                    'name' => $order->shippingMethods->name,
                    'price' => (int)$order->shippingMethods->price
                ],
                'total_price' => (int)$order->total_price,
                'status' => $order->statuses->name,
                'created_at' => $order->created_at->format('d-M-y'),
                'updated_at' => $order->updated_at->format('d-M-y'),
                'items' => OrderItemResource::collection($orderItems)
            ]
        ])->setStatusCode(200);
    }
}
