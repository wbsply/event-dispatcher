<?php

namespace WebSupply\EventDispatcher\EventDispatcher;

use Neos\Flow\Annotations as Flow;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;

#[Flow\Scope("singleton")]
class EventDispatcherFactory
{
    public function __construct(
        protected readonly EventListenerResolver $eventListenerResolver
    ) {}

    public function create(): EventDispatcherInterface
    {
        $eventDispatcher = new EventDispatcher();
        $listeners = $this->eventListenerResolver->getListeners();

        foreach ($listeners as $eventClassName => $eventListeners) {
            foreach ($eventListeners as $eventListener) {
                $eventDispatcher->addListener($eventClassName, new $eventListener->className());
            }
        }

        return $eventDispatcher;
    }

}
