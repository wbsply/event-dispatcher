<?php

namespace WebSupply\EventDispatcher\EventDispatcher;

use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Flow\Annotations as Flow;
use WebSupply\EventDispatcher\Annotations\EventListener;


#[Flow\Scope("singleton")]
class EventListenerResolver
{

    #[Flow\Inject]
    protected ObjectManagerInterface $objectManager;

    /**
     * @var array<string, ResolvedEventListener[]>
     */
    protected array $listeners = [];

    public function initializeObject() {
        $this->listeners = self::detectListeners($this->objectManager);
    }


    /**
     * @return ResolvedEventListener[][]
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    #[Flow\CompileStatic]
    public static function detectListeners(ObjectManagerInterface $objectManager): array
    {
        $listeners = [];
        /** @var ReflectionService $reflectionService */
        $reflectionService = $objectManager->get(ReflectionService::class);

        foreach ($reflectionService->getClassNamesByAnnotation(EventListener::class) as $listenerClassName) {
            $parameters = $reflectionService->getMethodParameters($listenerClassName, '__invoke');
            $event = reset($parameters);
            if ($event === false) {
                throw new \Exception(sprintf('Invalid listener in %s the method signature must accept an object', $listenerClassName), 1472500228);
            }

            $listeners[$event['class']][] = new ResolvedEventListener($event['class'], $listenerClassName);
        }
        return $listeners;
    }
}
