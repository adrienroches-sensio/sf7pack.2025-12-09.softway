<?php

namespace App\Repository;

use App\Entity\Conference;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    /**
     * @return list<Conference>
     *
     * @throws InvalidArgumentException When both dates are null (one must be provided).
     */
    public function searchBetweenDates(DateTimeImmutable|null $start, DateTimeImmutable|null $end): array
    {
        if (null === $start && null === $end) {
            throw new InvalidArgumentException('At least one date must be provided.');
        }

        $qb = $this->createQueryBuilder('conference');

        if (null !== $start) {
            $qb
                ->andWhere($qb->expr()->gte('conference.startAt', ':start'))
                ->setParameter('start', $start)
            ;
        }

        if (null !== $end) {
            $qb
                ->andWhere('conference.endAt <= :end')
                ->setParameter('end', $end)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return list<Conference>
     *
     * @throws InvalidArgumentException When both dates are null (one must be provided).
     */
    public function searchBetweenDatesWithRsm(DateTimeImmutable|null $start, DateTimeImmutable|null $end): array
    {
        if (null === $start && null === $end) {
            throw new InvalidArgumentException('At least one date must be provided.');
        }

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addEntityResult(Conference::class, 'conference');

        $whereClauses = [];
        $parameters = [];

        if (null !== $start) {
            $whereClauses[] = 'conference.startAt >= :start';
            $parameters['start'] = $start;
        }

        if (null !== $end) {
            $whereClauses[] = 'conference.endAt <= :end';
            $parameters['end'] = $end;
        }

        $whereClause = 'WHERE ' . implode(' AND ', $whereClauses);

        $sql = <<<"SQLITE"
        SELECT {$rsm->generateSelectClause()} FROM conference {$whereClause}
        SQLITE;

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query = $query->setParameters($parameters);

        return $query->getResult();
    }
}
