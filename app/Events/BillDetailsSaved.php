<?php

namespace App\Events;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillDetailsSaved
{
    use Dispatchable, SerializesModels;

    public $bill;
    public $user;
    public $billDetails;
    /**
     * Create a new event instance.
     *
     * @param  Bill  $bill
     * @param  array  $billDetails
     * @param  User  $user
     * @return void
     */
    public function __construct(Bill $bill, User $user, array $billDetails)
    {
        $this->bill = $bill;
        $this->user = $user;
        $this->billDetails = $billDetails;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
