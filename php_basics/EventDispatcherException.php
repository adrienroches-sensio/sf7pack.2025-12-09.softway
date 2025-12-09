<?php

declare(strict_types=1);

final class EventDispatcherException extends RuntimeException
{
    public const NO_LISTENERS_FOR_EVENT = 1;

    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function noListenersForEvent(string $eventName, Throwable|null $previous = null): self
    {
        return new self(
            message: "No listeners for event {$eventName}",
            code: self::NO_LISTENERS_FOR_EVENT,
            previous: $previous,
        );
    }
}
