<?php

namespace Knp\Event\Player;

use Knp\Event\Player;
use Knp\Event\Event;
use Knp\Event\AggregateRoot\CanBeReplayed;
use ReflectionClass;
use InvalidArgumentException;
use LogicException;
use Traversable;

class ReflectionBased implements Player
{
    public function replay(Traversable $events, $class)
    {
        $reflect = new ReflectionClass($class);

        if (!$reflect->implementsInterface(self::CAN_BE_REPLAYED)) {
            throw new InvalidArgumentException(sprintf('"%s" must implement "%s"', $class, self::CAN_BE_REPLAYED));
        }

        $object = $reflect->newInstanceWithoutConstructor();
        $methods = $object->getReplayableSteps();

        foreach ($events as $event) {
            $method = $reflect->getMethod($this->getMethodName($object, $reflect, $methods, $event));
            if ($method->isConstructor()) {
                $object = $reflect->newInstanceArgs($event->getAttributes());
                continue;
            }
            $method->invokeArgs($object, $event->getAttributes());
        }
        $object->popEvents();

        return $object;
    }

    private function getMethodName(CanBeReplayed $object, ReflectionClass $reflect, array $methods, Event $event)
    {
        if (isset($methods[$event->getName()])) {
            return $methods[$event->getName()];
        }

        $method = 'apply'.ucfirst($event->getName());
        if ($reflect->hasMethod($method)) {
            return $method;
        }

        throw new LogicException(sprintf('"%s" has no corresponding method listed in (%s).', $event->getName(), implode(', ', $methods + [$method])));
    }
}
