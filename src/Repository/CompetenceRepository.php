<?php

namespace App\Repository;

use App\Entity\Competence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Competence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competence[]    findAll()
 * @method Competence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competence::class);
    }

    public function findGroupeCompetenceByReferentiel($id, $id2)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.groupeCompetences', 'g')
            ->innerJoin('g.referentiels', 'r')
            ->andWhere('g.id = :val')
            ->setParameter('val', $id2)
            ->andWhere('r.id = :valeur')
            ->setParameter('valeur', $id)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Competence[] Returns an array of Competence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Competence
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
