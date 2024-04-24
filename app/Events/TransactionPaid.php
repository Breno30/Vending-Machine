<?php

namespace App\Events;

use App\Models\Machine;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TransactionPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;
    public $product;
    public $machine;

    /**
     * Create a new event instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $relationId = $this->transaction->machine_product_id;
        $relation = DB::table('machine_product')->find($relationId);

        $productId = $relation->product_id;
        $this->product = Product::findOrFail($productId);

        $machineId = $relation->machine_id;
        $this->machine = Machine::findOrFail($machineId);
    }

}
