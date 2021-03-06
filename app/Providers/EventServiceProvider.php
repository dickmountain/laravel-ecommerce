<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Order\OrderCreatedEvent' => [
            'App\Listeners\Order\ProcessPaymentListener',
            'App\Listeners\Order\EmptyCartListener',
        ],
	    'App\Events\Order\OrderPaymentFailedEvent' => [
		    'App\Listeners\Order\MarkOrderPaymentFailedListener',
	    ],
	    'App\Events\Order\OrderPaidEvent' => [
		    'App\Listeners\Order\CreateTransactionListener',
		    'App\Listeners\Order\MarkOrderProcessingListener',
	    ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
