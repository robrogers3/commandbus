<?php

namespace RobRogers\CommandBus\Eventing;

use Illuminate\Support\ServiceProvider;

// Register this in boot illuminate
class EventingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $listeners = $this->app['config']->get('events.listeners');

        foreach($listeners as $listener) {
            $this->app['events']->listen('App.*', $listener);
        }
    }
}
