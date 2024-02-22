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
                ->setParameter('site', $filterData['sites']);       
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
        if (isset($filterData['checkboxOrganizer']) && $filterData['checkboxOrganizer'] != null) {
            $q->andWhere('s.organisateur = :organizer')
                ->setParameter('organizer', $filterData['checkboxOrganizer']);
        }
        if (isset($filterData['checkboxRegistered']) && $filterData['checkboxRegistered'] != null) {
            $q->andWhere(':registered MEMBER OF s.participants')
                ->setParameter('checkboxRegistered', $filterData['checkboxRegistered']);
        }
        if (isset($filterData['checkboxNotRegistered']) && $filterData['checkboxNotRegistered'] != null) {
            $q->andWhere(':notRegistered NOT MEMBER OF s.participants')
                ->setParameter('checkboxNotRegistered', $filterData['checkboxNotRegistered']);
        }
        if (isset($filterData['checkboxPast']) && $filterData['checkboxPast']) {
            $q->andWhere('s.dateHeureDebut < :now')
                ->setParameter('now', new \DateTime());
                $q->andWhere('(s.etat = :etatPast OR s.etat = :etatCanceled) AND s.dateHeureDebut > :archiver')
                    ->setParameter('etatPast', 5)
                    ->setParameter('etatCanceled', 6)
                    ->setParameter('archiver', (new \DateTime())->sub(new \DateInterval('P1M')));
        } else {
            $q->andWhere('s.dateHeureDebut > :now')
                ->setParameter('now', new \DateTime());
        }
        
        return $q->getQuery()->getResult();
    }

    public function findSortieById($id) {
        $q = $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $q->getResult();
    }
}
