<?php

namespace App\Observers;

use App\Models\Orders;

class OrderObserver
{
    /**
     * Handle the Orders "created" event.
     */
    public function created(Orders $orders): void
    {
        //
    }

    /**
     * Handle the Orders "updated" event.
     */
    public function updated(Orders $orders): void
    {
        if($orders->isDirty('status') && $orders->status === "cancelled" ){
            $user = $orders->user;
            if(!$user){
                return;
            }

            $cancelledCount = $user->order()->where("status", "cancelled")->count();

            if($cancelledCount >= 5){
                $user->update([
                    "status" => false,
                ]);
            }
        }
    }

    /**
     * Handle the Orders "deleted" event.
     */
    public function deleted(Orders $orders): void
    {
        //
    }

    /**
     * Handle the Orders "restored" event.
     */
    public function restored(Orders $orders): void
    {
        //
    }

    /**
     * Handle the Orders "force deleted" event.
     */
    public function forceDeleted(Orders $orders): void
    {
        //
    }
}
