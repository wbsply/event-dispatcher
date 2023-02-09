<?php

namespace WebSupply\EventDispatcher\EventDispatcher;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
class ResolvedEventListener
{
    public function __construct(
        public readonly string $event,
        public readonly string $className
    ) {}
}
