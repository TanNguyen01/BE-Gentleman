<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $billDetails;
    public $user;
    public $bill;
    /**
     * Create a new message instance.
     */
    public function __construct($billDetails, $user, $bill)
    {
        $this->billDetails = $billDetails;
        $this->user = $user;
        $this->bill = $bill;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CHi tiet don hang',
        );
    }



    /**
     * Get the message content definition.
     */
    public function build(){
        return $this->view('emails.bill_confirmation')
            ->with([
                'billDetails' => $this->billDetails,
                'user'=> $this->user,
                'bill' => $this->bill,

            ]);
    }



    public function content(): Content
    {
        return new Content(
            view: 'emails.bill_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
