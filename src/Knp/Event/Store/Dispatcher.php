<?php

namespace Knp\Event\Store;

use Knp\Event\Store;
use Knp\Event\Event;
use Knp\Event\Dispatcher as EventDispatcher;

final class Dispatcher implements Store
{
    private $store;
    private $dispatcher;

    public function __construct(Store $store, EventDispatcher $dispatcher)
    {
        $this->store = $store;
        $this->dispatcher = $dispatcher;
    }

    public function addSet(Event\Set $events)
    {
        $this->store->addSet($events);

        foreach ($events->all() as $event) {
            $this->dispatcher->dispatch($event);
        }
    }

    public function findBy($class, $id)
    {
        return $this->store->findBy($class, $id);
    }

}
