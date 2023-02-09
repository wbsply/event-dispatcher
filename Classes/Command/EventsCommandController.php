<?php

namespace WebSupply\EventDispatcher\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use WebSupply\EventDispatcher\EventDispatcher\EventListenerResolver;

final class EventsCommandController extends CommandController
{
    public function __construct(
        protected readonly EventListenerResolver $eventListenerResolver
    )
    {
        parent::__construct();
    }

    public function listCommand(string $event = null)
    {
        foreach ($this->eventListenerResolver->getListeners() as $eventClassName => $eventListeners)
        {
            if ($event !== null && $eventClassName !== $event) {
                continue;
            }
            $this->outputLine();
            $rows = [];

            foreach ($eventListeners as $listenerClassName) {
                $rows[] = [$listenerClassName->className];
            }
            $this->output->outputTable($rows, ['Listener class'], $eventClassName);
            $this->outputLine();
        }
    }
}
