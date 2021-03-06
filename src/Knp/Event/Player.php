<?php

namespace Knp\Event;

use Knp\Event\Event;
use Traversable;

interface Player
{
    const CAN_BE_REPLAYED = 'Knp\Event\Emitter\CanBeReplayed';

    public function replay(Traversable $events, $class);
}
