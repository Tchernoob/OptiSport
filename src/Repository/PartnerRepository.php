<?php

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partner>
 *
 * @method Partner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partner[]    findAll()
 * @method Partner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    public function add(Partner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Partner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findMod($mod)
    {
        $qb = $this->createQueryBuilder('p')
        ->select('p')
        ->innerJoin('p.mods', 'm')
            ->where('m.id = :mod')
            ->setParameter('mod', $mod);

        return $qb->getQuery()->getResult();
    }

    public function findByCriteria($val) {
        $qb = $this->createQueryBuilder('p')
        ->select('p')
        ->where('p.name like :val')
        ->setParameter('val' , '%' . $val . '%'); 

        return $qb->getQuery()->getResult();
    }

    public function findByStatus($val) {
        $qb = $this->createQueryBuilder('p')
        ->select('p')
        ->where('p.is_active = :val')
        ->setParameter('val' , $val ); 

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Partner[] Returns an array of Partner objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Partner
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
