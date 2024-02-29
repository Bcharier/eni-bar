<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\WatchDogMessage;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class WatchDogMessageHandler
{
    public function __construct(private SortieRepository $sortieRepository, private EntityManagerInterface $manager) 
    {

    }

    public function __invoke(WatchDogMessage $message)
    {

        //selectionner toutes les sorties qui ont une date de début inférieur à maintenant, et que leur état soit "plublié", changer leur état à "en cours" 
        $res = $this->sortieRepository->findBy(['date_heure_debut' => new Date()]);
        foreach($sortie as $res) {
            $sortie->setEtat($entityManager->getReference('App\Entity\Etat', 3));
        }
        //$newSite = new Site();
        //$newSite->setNom("Hello");
        $this->manager->persist($newSite);
        $this->manager->flush();
        //$services = $message->getServices();
        // logic to handle your services below
        // ...
    }
}