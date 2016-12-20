<?php

namespace RobRogers\CommandBus\Eventing;

class EventGenerator
{
    protected $pendingEvents = array();
    /**
     * @var \RobRogers\CommandBus\Eventing\EventDispatcher
     */
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {

        $this->dispatcher = $dispatcher;
    }

    public function __destruct()
    {
        $this->dispatcher->dispatch($this->releaseEvents());
    }

    public function register($event)
    {
        $this->pendingEvents[] = $event;

    }

    public function releaseEvents()
    {
        $events = $this->pendingEvents;

        $this->pendingEvents = array();

        return $events;
    }
}
