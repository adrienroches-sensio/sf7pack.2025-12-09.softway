<?php

declare(strict_types=1);

require __DIR__ . '/EventListenerInterface.php';
require __DIR__ . '/EventDispatcher.php';

$eventDispatcher = new EventDispatcher();

$eventDispatcher->addListener('foo', function (): void {
    echo 'foo' . PHP_EOL;
});

$eventDispatcher->addListener('stdClass', function (stdClass $event): void {
    echo $event->name . PHP_EOL;
});

$eventDispatcher->addListener('stdClass', new class implements EventListenerInterface
{
    public function handle(object $event): void
    {
        echo 'From listener interface handle' . PHP_EOL;
    }
}, +12);

$event = new stdClass();
$event->name = 'bar';

$eventDispatcher->dispatch($event);
