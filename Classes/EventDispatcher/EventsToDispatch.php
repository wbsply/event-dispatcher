<?php

declare(strict_types=1);

namespace WebSupply\EventDispatcher\EventDispatcher;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class EventsToDispatch implements \IteratorAggregate
{
    /**
     * @var array<int, object>
     */
    private array $events;

    private function __construct(...$events)
    {
        $this->events = $events;
    }

    public function append($event)
    {
        $events = $this->events;
        $this->events = array_merge($events, [$event]);
    }

    public static function fromArray(array $events): self
    {
        return new self(...$events);
    }

    public static function empty(): self
    {
        return self::fromArray([]);
    }

    /**
     * @return \Traversable<object>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->events);
    }

}
