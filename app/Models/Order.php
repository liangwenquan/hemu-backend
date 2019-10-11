<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const ORDER_CONFIRM_STATUS = 1;
    const ORDER_CANCEL_STATUS = 0;
    const ORDER_REMARK_STATUS = 10;
    const ORDER_REMARKED_STATUS = 20;

    protected static $ORDER_STATUS_TEXT = [
        self::ORDER_CANCEL_STATUS => '已取消',
        self::ORDER_CONFIRM_STATUS => '待付款',
        self::ORDER_REMARK_STATUS => '待评价',
        self::ORDER_REMARKED_STATUS => '已评价',
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'remark',
        'shop_id',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function getOrderStatusStrAttribute()
    {
        return self::$ORDER_STATUS_TEXT[$this->status];
    }
}
