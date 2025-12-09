<?php

declare(strict_types=1);

require __DIR__ . '/EventDispatcher.php';

$eventDispatcher = new EventDispatcher();

$eventDispatcher->addListener('foo', function (): void {
    echo 'foo' . PHP_EOL;
});

$eventDispatcher->addListener('stdClass', function (stdClass $event): void {
    echo $event->name . PHP_EOL;
});

$event = new stdClass();
$event->name = 'bar';

$eventDispatcher->dispatch($event);
