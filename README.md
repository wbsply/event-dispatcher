# PSR-14 Event Dispatcher for Neos Flow

Symfony EventDispatcher integrated with auto-discover!

## Installation

`composer require websupply/event-dispatcher`

## PSR-14 integration

This package integrates the PSR-14 functionality.
But instead of reinventing the wheel, the package implements `symfony/event-dispatcher`.

## How to use it
With the configuration in `Configuration/Objects.yaml` you can inject/instantiate the `Psr\EventDispatcher\EventDispatcherInterface` interface

```php
use Psr\EventDispatcher\EventDispatcherInterface;

public function __construct(
    protected readonly EventDispathcerInterface $eventDispathcer
) {}

public function method(string $argument): void
{
    // what ever business logic goes before the dispatching
    $this->eventDispatcher->dispatch(new ProductWasCreated($argument));
}
````

## The Event

A event to dispatch is stripped down to be a plain PHP object and could look like this

```php
namespace Project;

use ValueObject;

final class ProductWasCreated
{
    public function __construct(
        public readonly ValueObject\ProductId $id,
        public readonly ValueObject\ProductName $name
    ) {}
}
```

## The Event Listener
Similar to the Event class, the EventListener is a plain PHP object, but with a important `#[EventListener]` annotation brought to you by the `WebSupply\EventDispatcher\Annotations\EventListener` class.

It must implement the `__invoke(..)` method, with the corresponding event class as argument

This example injects a PSR logger and adds a note about the newly created product

```php
use WebSupply\EventDispatcher\Annotations\EventListener;
use ProductWasCreated;
use Psr\Log\LoggerInterface;

#[EventListener]
final class ProductWasCreatedListener
{

    public function __construct(protected readonly LoggerInterface $logger)
    {}
    public function __invoke(ProductWasCreated $event)
    {
        $this->logger->info('Product was created', ['id' => (string) $event->id]);
    }
}
```

## CLI tool for overview

The command `./flow events:list` gives you a list of resolved event and listeners.

If you end up having a ton of events and listeners, you can filter the list, to show listeners based on a single event, by adding a `--event "<event-class>"` arguments

```shell
./flow commands:list --command "ProductWasCreated"
```

## Support and sponsoring
Work on this package is supported by the danish web company **WebSupply ApS** 
