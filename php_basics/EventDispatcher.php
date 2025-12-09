<?php

declare(strict_types=1);

final class EventDispatcher
{
    private array $listeners = [];

    public function addListener(string $eventName, callable|EventListenerInterface $listener, int $priority = 0): void
    {
        $this->listeners[$eventName] ??= [];

        if ($listener instanceof EventListenerInterface) {
            $listener = $listener->handle(...);
        }

        $this->listeners[$eventName][] = [$listener, $priority];
    }

    public function dispatch(object $event, string|null $eventName = null): object
    {
        $eventName ??= $event::class;

        foreach ($this->getListeners($eventName) as $listener) {
            $listener($event);
        }

        return $event;
    }

    private function getListeners(string $eventName): array
    {
        $listeners = $this->listeners[$eventName] ?? throw EventDispatcherException::noListenersForEvent($eventName);

        usort($listeners, static function (array $a, array $b): int {
            [, $priorityA] = $a;
            [, $priorityB] = $b;

            return $priorityB <=> $priorityA;
        });

        return array_column($listeners, 0);
    }
}
