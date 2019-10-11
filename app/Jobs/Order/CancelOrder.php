<?php

namespace App\Jobs\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;

class CancelOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $delay)
    {
        $this->order = $order;
        $this->delay($delay);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //判断订单是否支付
        if ($this->order->payed_at) {
            return;
        }

        try {
            $this->order->update([
                'status' => Order::ORDER_CANCEL_STATUS
            ]);
        } catch (\Exception $e) {
            app('log')->error('', ['order_id' => $this->order->id]);
        }
    }
}
