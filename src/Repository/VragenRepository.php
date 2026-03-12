<?php

namespace App\Repository;

use App\Entity\Vragen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vragen>
 */
class VragenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vragen::class);
    }

    public function findFiltered(string $filter = 'all', string $search = '', ?int $profielId = null, string $tag = ''): array
    {
        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.profiel', 'p')
            ->leftJoin('v.tags', 't');

        if ($search !== '') {
            $qb->andWhere('v.titel LIKE :search OR v.beschrijving LIKE :search OR p.name LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($tag !== '') {
            $qb->andWhere('t.naam = :tag')->setParameter('tag', $tag);
        }

        if ($filter === 'open') {
            $qb->andWhere('v.status = :status')->setParameter('status', 'Open');
        } elseif ($filter === 'closed') {
            $qb->andWhere('v.status = :status')->setParameter('status', 'Closed');
        } elseif ($filter === 'my' && $profielId) {
            $qb->andWhere('v.profiel = :profielId')->setParameter('profielId', $profielId);
        }

        return $qb->orderBy('v.id', 'DESC')->getQuery()->getResult();
    }
}

