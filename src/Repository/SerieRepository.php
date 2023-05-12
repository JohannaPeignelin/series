<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries(){
//            //EN DQL
//            $entitymanager = $this->getEntityManager();
//            $dql = "SELECT s FROM App\Entity\Serie
//                    AS s WHERE s.vote> 8.5
//                    AND s.popularity > 200
//                    ORDER BY s.popularity DESC";
//            $query = $entitymanager->createQuery($dql);

            //Avec le Query Builder
            $qb = $this->createQueryBuilder('s');
            $qb ->addOrderBy('s.popularity', 'DESC')
                ->andWhere('s.vote >= 8')
                ->andWhere('s.popularity > 100');

            $query = $qb->getQuery();

            //pareil pour les deux
            $query->setMaxResults(50);
            return $query->getResult();
        }
}