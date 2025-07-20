<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreateRequest;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderUserResource;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

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
                'billing_address' => [
                    'first_name' => $order->billingAddresses->first_name,
                    'last_name' => $order->billingAddresses->last_name,
                    'address' => $order->billingAddresses->address,
                    'appartment_suite' => $order->billingAddresses->appartment_suite,
                    'city' => $order->billingAddresses->city,
                    'province' => $order->billingAddresses->province,
                    'country' => $order->billingAddresses->country
                ],
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

    public function createNewOrder(OrderCreateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'create-address: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ]));
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $data = $request->validated();

        try {
            DB::beginTransaction();

            $billingAddress = BillingAddress::create([
                'first_name' => $data['billing_address']['first_name'],
                'last_name' => $data['billing_address']['last_name'],
                'address' => $data['billing_address']['address'],
                'appartment_suite' => $data['billing_address']['appartment_suite'],
                'city' => $data['billing_address']['city'],
                'province' => $data['billing_address']['province'],
                'postal_code' => $data['billing_address']['postal_code'],
                'country' => $data['billing_address']['country'],
            ]);

            $order = Order::create([
                'user_id' => $user->id,
                'billling_address_id' => $billingAddress->id,
                'shipping_method_id' => $data['shipping_method_id'],
                'total_price' => $data['total_price'],
                'status_id' => 1,
            ]);

            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $data['product_variant_id'],
                    'quantity' => $data['quantity'],
                    'total_price' => $data['total_price']
                ]);
            }

            Db::commit();

            return response()->json([
                'message' => 'Order successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'created_at' => $order->created_at->format('d-m-Y'),
                ]
            ])->setStatusCode(200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create order.',
                'error' => $ex->getMessage()
            ])->setStatusCode(500);
        }
    }
}
