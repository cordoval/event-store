<?php

namespace Knp\Event;

use Knp\Event\Event;
use Knp\Event\Emitter;

interface Store
{
    public function addSet(Event\Set $events);

    /**
     * @throws Knp\Event\Exception\Store\NoResult
     **/
    public function findBy($class, $id);
}
