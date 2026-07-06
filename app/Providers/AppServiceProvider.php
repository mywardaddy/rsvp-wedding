<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        User::observe(UserObserver::class);

        // Share managed event data with layout views for navbar indicator
        View::composer('layouts.app', function ($view) {
            $managedEvent = null;

            if (auth()->check() && auth()->user()->isSuperadmin()) {
                $eventId = session('managed_event_id');
                if ($eventId) {
                    $managedEvent = Event::find($eventId);
                }
            }

            $view->with('managedEvent', $managedEvent);
        });
    }
}
