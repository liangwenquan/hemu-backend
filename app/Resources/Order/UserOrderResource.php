<?php/** * Created by PhpStorm. * User: mytoken * Date: 2019-06-27 * Time: 11:24 */namespace App\Resources\Order;use App\Ship\Http\Json\ApiResource;class UserOrderResource extends ApiResource{    public $resources;    public function toArray($request)    {        return [            'orderNumber'  => $this->resource->id,            'order_total' => $this->resource->amount,            'status' => $this->resource->status,            'remark' => $this->resource->remark,            'status_str' => $this->resource->orderStatusStr,            'id' => $this->resource->id,        ];    }}