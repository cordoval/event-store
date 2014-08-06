<?php

namespace Knp\Event\Player;

use Knp\Event\Player;
use Knp\Event\Event;

class Aggregate implements Player
{
    private $players;
    private $default;

    public function __construct(array $players, Player $default = null)
    {
        $this->players = $players;
        $this->default = $default;
    }

    public function replay(array $events, $class)
    {
        if (isset($this->players[$class])) {
            return $this->players[$class]->replay($events, $class);
        }

        if (!$this->default) {
            throw new \InvalidArgumentException("$class is not registered to be replayable");
        }

        $this->default->replay($events, $class);
    }
}
