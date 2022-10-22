<?php

namespace App\Repository;

use App\Entity\Mods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mods>
 *
 * @method Mods|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mods|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mods[]    findAll()
 * @method Mods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mods::class);
    }

    public function add(Mods $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mods $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // permet de récupérer seulement les modules actifs pour les formulaires de créations Partner et Structure
    public function getModsActive()
    {
        return $this->createQueryBuilder('t')
            ->where('t.is_active = true')
            ->orderBy('t.id', 'ASC');
    }



//    /**
//     * @return Mods[] Returns an array of Mods objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mods
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
