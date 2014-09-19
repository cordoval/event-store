<?php

namespace spec\Knp\Event\Store\Concurrency;

use PhpSpec\ObjectBehavior;
use Knp\Event\Store;
use Prophecy\Prophet;
use Knp\Event\Event;
use Knp\Event\Emitter;

class OptimisticSpec extends ObjectBehavior
{
    function let()
    {
        $store = (new Prophet)->prophesize('Knp\Event\Store')->willImplement('Knp\Event\Store\IsVersioned');
        $this->beConstructedWith($store->reveal());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Event\Store\Concurrency\Optimistic');
    }

    function it_allows_same_version(Event $event, Emitter $emitter)
    {
        $this->addSet(new Event\Set($emitter->getWrappedObject(), [$event->getWrappedObject()]));
    }

    function it_refuses_conflictual_sets(Event $event, Event $conflictual, Emitter $emitter)
    {
        $this->addSet(new Event\Set($emitter->getWrappedObject(), [$event->getWrappedObject()]));
        $this->shouldThrow('Knp\Event\Exception\Concurrency\Optimistic\Conflict', 'addSet', [new Event\Set($emitter->getWrappedObject(), [
            $event->getWrappedObject(),
            $conflictual->getWrappedObject(),
        ])]);
    }
}
