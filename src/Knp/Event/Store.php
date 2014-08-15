<?php

namespace Knp\Event;

use Knp\Event\Event;

interface Store
{
    public function add(Event $event);

    public function findBy($class, $id);
}
