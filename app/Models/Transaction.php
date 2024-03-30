<?php

namespace App\Models;

use App\Events\TransactionPaid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['machine_product_id', 'type', 'identifier', 'status'];

    public function markAsPaid()
    {
        // Dispatch event
        event(new TransactionPaid($this));
    }
}
