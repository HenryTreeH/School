<?php

namespace App\Repository;

use App\Entity\ScrapeLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScrapeLog>
 */
class ScrapeLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScrapeLog::class);
    }

    /**
     * Retrieves scrape statistics (example: count by status)
     * @return array
     */
    public function getScrapeStats(): array
    {
        // Query for total scrapes, successful scrapes, and failed scrapes
        $totalScrapes = $this->createQueryBuilder('sl')
            ->select('COUNT(sl.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $successfulScrapes = $this->createQueryBuilder('sl')
            ->select('COUNT(sl.id)')
            ->where('sl.status = :status')
            ->setParameter('status', 'success')
            ->getQuery()
            ->getSingleScalarResult();

        $failedScrapes = $this->createQueryBuilder('sl')
            ->select('COUNT(sl.id)')
            ->where('sl.status = :status')
            ->setParameter('status', 'failure')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalScrapes' => $totalScrapes,
            'successfulScrapes' => $successfulScrapes,
            'failedScrapes' => $failedScrapes,
        ];
    }

    public function getRecentLogs(int $maxResults = 10): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }
    public function findRecentLogs(int $limit = 10): array
    {
        return $this->createQueryBuilder('sl')
            ->orderBy('sl.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
