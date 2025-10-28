<?php

namespace App\Listeners;

use App\Jobs\SendAccountRegisteredEmail;
use App\Jobs\SendAccountVerificationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AccountRegistered implements ShouldQueue
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
    public function handle(Registered $event): void
    {
        SendAccountRegisteredEmail::dispatch($event->user);
        SendAccountVerificationEmail::dispatch($event->user);
    }
}
