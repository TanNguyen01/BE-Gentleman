<?php

namespace App\Listeners;

use App\Events\BillDetailsSaved;
use App\Mail\BillConfirmationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBillDetailsEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BillDetailsSaved $event): void
    {
        $bill = $event->bill;
        $user = $event->user;
        $billDetails = $event->billDetails;


        Mail::to($user->email)->queue(new BillConfirmationMail($bill, $user, $billDetails));
    }
}
