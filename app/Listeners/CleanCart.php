<?php

namespace App\Listeners;

use App\Cart;
use App\Events\orderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CleanCart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  orderCreated  $event
     * @return void
     */
    public function handle(orderCreated $event)
    {
        $order=$event->order;//dday la cach goi de lay gia tri tu order sang gui email
        session()->forget(["my_cart"]);
        Cart::where("user_id",$order->__get("user_id"))->update(["is_checkout"=>false]);
    }
}
