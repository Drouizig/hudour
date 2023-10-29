<?php

namespace App\Repository;

use App\Entity\Affix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affix>
 *
 * @method Affix|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affix|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affix[]    findAll()
 * @method Affix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affix::class);
    }

//    /**
//     * @return Affix[] Returns an array of Affix objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Affix
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
