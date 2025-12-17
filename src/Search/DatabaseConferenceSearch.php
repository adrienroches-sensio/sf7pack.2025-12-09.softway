<?php

declare(strict_types=1);

namespace App\Search;

use App\Repository\ConferenceRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

//#[AsAlias(id: ConferenceSearchInterface::class)]
final class DatabaseConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceRepositoryInterface $conferenceRepository,
    ) {
    }

    public function searchByName(string|null $name = null): array
    {
        $name = trim($name ?? '');

        if ('' === $name) {
            return $this->conferenceRepository->listAll();
        }

        return $this->conferenceRepository->searchByName($name);
    }
}
