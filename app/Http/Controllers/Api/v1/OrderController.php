<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use App\Jobs\Order\CancelOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Queries\IndexQueryBuilder;
use App\Resources\Order\UserOrderResource;
use App\Resources\Order\UserOrdersResource;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function orderPreview()
    {
        $goods = request()->post();

        $orderAmount = 0;
        foreach ($goods['goods'] as $value) {
            $value['price'] = Product::query()->where('id', $value['goodsId'])->firstOrFail()->price;
            $orderAmount += $value['number'] * $value['price'];
        }

        return $this->packOk([
            'goods_list' => $goods['goods'],
            'order_total' => $orderAmount
        ]);
    }

    public function userOrderDetail($orderId)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->pack(401, '用户信息失效，请重新登陆');
        }

        $order = Order::query()->where('id', $orderId)->firstOrFail();

        return new UserOrderResource($order);
    }

    public function userOrderList()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->pack(401, '用户信息失效，请重新登陆');
        }

        $orderStatus = request()->get('status');

        $orders = (new IndexQueryBuilder(Order::class))
            ->build()
            ->with('orderItems')
            ->with('orderItems.product')
            ->where('status', $orderStatus)
            ->where('user_id', $user->id)
            ->select('orders.*')
            ->latest()
            ->paginate($this->pagesize);

        return UserOrdersResource::collection($orders);
    }

    public function create()
    {
        $cartInfo = request()->post();

        $user = auth()->user();

        if (!$user) {
            return $this->pack(401, '用户信息失效，请重新登陆');
        }

        DB::beginTransaction();

        $goodsParams = [];
        $orderAmount = 0;
        foreach ($cartInfo['goods'] as $goods) {
            $goodsDetail = Product::query()->where('id', $goods['goodsId'])->firstOrFail();
            $orderAmount += $goods['number'] * $goodsDetail->price;
            $goodsParams[] = [
                'product_id' => $goods['goodsId'],
                'quantity' => $goods['number'],
                'price' => $goodsDetail->price,
                'payed_price' => $goodsDetail->price,
            ];
        }

        try {
            $orderParams = [
                'user_id' => $user->id,
                'amount' => $orderAmount,
                'status' => Order::ORDER_CONFIRM_STATUS,
            ];

            if ($cartInfo['remark']) {
                $orderParams['remark'] = $cartInfo['remark'];
            }

            $order = Order::create($orderParams);
            $orderId = $order->id;

            $goodsParams = array_map(function ($goods) use ($orderId) {
                $goods['order_id'] = $orderId;
                return $goods;
            }, $goodsParams);

            foreach ($goodsParams as $goods) {
                OrderItem::create($goods);
            }

            DB::commit();

            //定时取消未支付订单
            $this->dispatch(new CancelOrder($order, config('queue.ttl.order_ttl')));
        } catch (\Exception $e) {
            info($e->getMessage());
            DB::rollBack();
            return $this->pack(40500, '订单保存失败');
        }

        return $this->packOk([
            'order_total' => $orderAmount
        ]);
    }
}
