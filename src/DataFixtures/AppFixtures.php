<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Entity\Volunteering;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use function array_map;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sensioLabs = $this->createOrganization('SensioLabs');
        $manager->persist($sensioLabs);

        for ($i = 15; $i <= 25; $i++) {
            $year = '20' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $symfonyLive = $this->createConference(
                name: "SymfonyLive {$year}",
                start: new DateTimeImmutable("{$year}-03-22"),
                organizations: [$sensioLabs],
                description: "Annual conference in Paris ({$year}).",
            );
            $manager->persist($symfonyLive);
        }

        $symfony = $this->createOrganization('Symfony SAS');
        $manager->persist($symfony);

        $symfonyCon2025 = $this->createConference(
            name: 'SymfonyCon 2025 (multiple organizations)',
            start: new DateTimeImmutable('2025-11-27'),
            organizations: [$symfony, $sensioLabs],
        );
        $manager->persist($symfonyCon2025);

        $conferenceWithoutOrganizations = $this->createConference(
            name: 'Conference without organizations',
            start: new DateTimeImmutable('2020-02-12'),
            accessible: false,
            prerequisites: 'Some prerequisites',
        );
        $manager->persist($conferenceWithoutOrganizations);

        $conferenceWithVolunteersStart = new DateTimeImmutable('2026-02-12');
        $volunteer1 = $this->createVolunteer($conferenceWithVolunteersStart);
        $volunteer2 = $this->createVolunteer($conferenceWithVolunteersStart);
        $volunteer3 = $this->createVolunteer($conferenceWithVolunteersStart);
        $volunteer4 = $this->createVolunteer($conferenceWithVolunteersStart);
        $volunteer5 = $this->createVolunteer($conferenceWithVolunteersStart);
        $volunteers = [$volunteer1, $volunteer2, $volunteer3, $volunteer4, $volunteer5];

        array_map($manager->persist(...), $volunteers);

        $conferenceWithVolunteers = $this->createConference(
            name: 'Conference with volunteers',
            start: new DateTimeImmutable('2026-02-12'),
            volunteers: $volunteers,
        );
        $manager->persist($conferenceWithVolunteers);

        $manager->flush();
    }

    private function createOrganization(
        string $name,
    ): Organization {
        $organization = new Organization();
        $organization->setName($name);
        $organization->setPresentation("Random presentation for {$name}.");
        $organization->setCreatedAt(new DateTimeImmutable('2025-11-05'));

        return $organization;
    }

    private function createConference(
        string $name,
        DateTimeImmutable $start,
        array $organizations = [],
        bool $accessible = true,
        string|null $description = null,
        string|null $prerequisites = null,
        array $volunteers = [],
    ): Conference
    {
        $conference = new Conference();
        $conference->setName($name);
        $conference->setAccessible($accessible);
        $conference->setDescription($description ?? "Random description for {$name}.");
        $conference->setStartAt($start);
        $conference->setEndAt($start->modify('+2 days'));
        $conference->setPrerequisites($prerequisites);

        foreach ($organizations as $organization) {
            $conference->addOrganization($organization);
        }

        foreach ($volunteers as $volunteering) {
            $conference->addVolunteering($volunteering);
        }

        return $conference;
    }

    private function createVolunteer(
        DateTimeImmutable $startAt,
        DateTimeImmutable|null $endAt = null,
    ): Volunteering {
        $volunteering = new Volunteering();
        $volunteering->setStartAt($startAt);
        $volunteering->setEndAt($endAt ?? $startAt->modify('+1 day'));

        return $volunteering;
    }
}
