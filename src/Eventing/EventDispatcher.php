<?php

namespace RobRogers\CommandBus\Eventing;

use Illuminate\Support\Facades\App;


class EventDispatcher
{
    public function dispatch(array $events)
    {
        /** @var \Illuminate\Events\Dispatcher; $dispatcher */
        $dispatcher = App::Make('Dispatcher');

        foreach ($events as $event) {
            $eventName = $this->getEventName($event);

            $dispatcher->fire($eventName, $event);
        }
    }

    /**
     * @param $event
     * @return mixed
     */
    protected function getEventName($event)
    {
        return str_replace('\\', '.', get_class($event));

    }
}
