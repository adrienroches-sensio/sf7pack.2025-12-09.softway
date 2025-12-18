<?php

declare(strict_types=1);

namespace App\Controller\Api\Conference;

use App\Search\ConferenceSearchInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/conferences', methods: ['GET'])]
final class ListController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ConferenceSearchInterface $conferenceSearch,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $conferences = $this->conferenceSearch->searchByName($request->query->getString('name'));

        return new JsonResponse($this->serializer->serialize(
            $conferences,
            'json',
            [
                'groups' => ['conference:list'],
            ]
        ), json: true);
    }
}
