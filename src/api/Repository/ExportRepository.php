<?php

namespace App\Repository;

use App\Entity\Export;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Export>
 */
class ExportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Export::class);
    }

    public function findWithFilters(array $filters, int $page, int $limit): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.property', 'p')
            ->leftJoin('e.gateway', 'g')
            ->orderBy('e.updatedAt', 'DESC');

        if (!empty($filters['gateway_code'])) {
            $qb->andWhere('g.code = :gateway_code')->setParameter('gateway_code', $filters['gateway_code']);
        }
        if (!empty($filters['status'])) {
            $qb->andWhere('e.status = :status')->setParameter('status', $filters['status']);
        }
        if (!empty($filters['property_id'])) {
            $qb->andWhere('p.id = :property_id')->setParameter('property_id', $filters['property_id']);
        }

        $totalQb = clone $qb;
        $totalQb->select('COUNT(e.id)')
                ->resetDQLPart('orderBy');

        $total = $totalQb->getQuery()->getSingleScalarResult();
        $results = $qb
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return [$results, (int) $total];
    }
}
