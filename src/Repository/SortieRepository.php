<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findAllSortie() {
        $q = $this->createQueryBuilder('s')
            ->orderBy('s.dateHeureDebut', 'DESC')
            ->getQuery();

        return $q->getResult();
    }

    public function findFilteredSortie($filterData) {
        $q = $this->createQueryBuilder('s')
            ->orderBy('s.dateHeureDebut', 'DESC');
            $q->andWhere('s.site = :site')
                ->setParameter('site', $filterData['site']);
        if ($filterData['nameSearch'] != null) {
            $q->andWhere('s.nom LIKE :nameSearch')
                ->setParameter('nameSearch', '%' . $filterData['nameSearch'] . '%');
        }
        if ($filterData['dateStart'] != null) {
            $q->andWhere('s.dateHeureDebut >= :dateStart')
                ->setParameter('dateStart', $filterData['dateStart']);
        }
        if ($filterData['dateEnd'] != null) {
            $q->andWhere('s.dateHeureDebut <= :dateEnd')
                ->setParameter('dateEnd', $filterData['dateEnd']);
        }
        if (isset($filterData['organizer']) && $filterData['organizer'] != null) {
            $q->andWhere('s.organisateur = :organizer')
                ->setParameter('organizer', $filterData['organizer']);
        }
        if (isset($filterData['registered']) && $filterData['registered'] != null) {
            $q->andWhere(':registered MEMBER OF s.participants')
                ->setParameter('registered', $filterData['registered']);
        }
        if (isset($filterData['notRegistered']) && $filterData['notRegistered'] != null) {
            $q->andWhere(':notRegistered NOT MEMBER OF s.participants')
                ->setParameter('notRegistered', $filterData['notRegistered']);
        }
        if (isset($filterData['passed']) && $filterData['passed']) {
            $q->andWhere('s.dateHeureDebut < :now')
                ->setParameter('now', new \DateTime());
        } else {
            $q->andWhere('s.dateHeureDebut > :now')
                ->setParameter('now', new \DateTime());
        }

        return $q->getQuery()->getResult();
    }
}
