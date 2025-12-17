<?php

declare(strict_types=1);

namespace App\Search;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(ConferenceSearchInterface::class)]
final class DebounceConferenceSearch implements ConferenceSearchInterface
{
    private array $debounce = [];

    public function __construct(
        private readonly ConferenceSearchInterface $inner
    ) {
    }

    public function searchByName(?string $name = null): array
    {
        $name = trim($name ?? '');

        if ('' === $name) {
            return $this->inner->searchByName();
        }

        return $this->debounce[$name] ??= $this->inner->searchByName($name);
    }
}
