<?php
declare(strict_types=1);

namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\ParsedPageInfo;

class ParsedPageInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParsedPageInfo::class);
    }

    public function getAllOrderedByImages(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.images', 'DESC')
            ->getQuery();

        return $qb->execute();
    }
}