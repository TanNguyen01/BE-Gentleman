<?php

namespace App\Jobs;

use App\Mail\BillConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{



    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    protected $billDetails;
    protected $user;
    protected $bill;



    /**
     * Create a new job instance.
     */
    public function __construct($billDetails,$user,$bill)
    {

        $this->billDetails = $billDetails;
        $this->user = $user;
        $this->bill = $bill;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new BillConfirmationMail($this->billDetails,$this->user,$this->bill));
    }
}
