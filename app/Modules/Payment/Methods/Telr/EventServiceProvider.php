<?php

namespace App\Modules\Payment\Methods\Telr;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

use App\Modules\Payment\Events\TelrCreateRequestEvent;
use App\Modules\Payment\Events\TelrRecieveTransactionResponseEvent;
use App\Modules\Payment\Listeners\CreateTransactionListener;
use App\Modules\Payment\Listeners\SaveTransactionResponseListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TelrCreateRequestEvent ::class => [
            CreateTransactionListener::class,
        ],
        // Register listener after receive response from telr
        TelrRecieveTransactionResponseEvent::class => [
            SaveTransactionResponseListener::class
        ],
    ];

}
