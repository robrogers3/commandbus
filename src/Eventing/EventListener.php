<?php

namespace RobRogers\CommandBus\Eventing;

use ReflectionClass;

class EventListener
{
    public function handle($event)
    {
        $eventName = $this->getEventName($event);

        $method = $this->isListenerRegistered($eventName);
        if ($method) {
            //return call_user_func(array($this, "when". $eventName), $event);
            return $this->$method($event);
        }
    }

    /**
     * @param $event
     * @return string
     */
    protected function getEventName($event)
    {
        $reflectionClass = new ReflectionClass($event);

        $eventName = $reflectionClass->getShortName();

        return $eventName;
    }

    /**
     * @param $eventName
     * @return mixed
     */
    protected function isListenerRegistered($eventName)
    {
        $method = "when{$eventName}";

        return method_exists($this, $method) ? $method : '';
    }
}
