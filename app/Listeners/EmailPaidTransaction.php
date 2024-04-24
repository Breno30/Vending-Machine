<?php

namespace App\Listeners;

use App\Events\TransactionPaid;
use App\Mail\TransactionPaid as MailTransactionPaid;
use Illuminate\Support\Facades\Mail;

class EmailPaidTransaction
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
    public function handle(TransactionPaid $event): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        Mail::to($adminEmail)->send(new MailTransactionPaid($event));
    }
}
