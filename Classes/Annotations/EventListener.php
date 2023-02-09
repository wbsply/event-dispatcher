<?php

namespace WebSupply\EventDispatcher\Annotations;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class EventListener
{
    public function __construct() {}
}
