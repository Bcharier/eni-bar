<?php

namespace App\Controller;

// Assurez-vous d'importer la classe AbstractController
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Lieu;
use App\Repository\LieuRepository;
use App\Entity\Ville;
use App\Repository\VilleRepository;

class ApiController extends AbstractController
{
    #[Route('/get-lieux-by-ville/{villeId}', name: 'get_lieux_by_ville')]
    public function getLieuxByVille(int $villeId, LieuRepository $lieuRepository): JsonResponse
    {
        $lieux = $lieuRepository->findBy(['ville' => $villeId]);
        $lieuxArray = [];
        foreach ($lieux as $lieu) {
            //$lieuxArray[$lieu->getId()] = $lieu->getNom(); // Adapté en fonction de la structure de votre entité Lieu
            $lieu2 = new \stdClass();
            $lieu2->id = $lieu->getId();
            $lieu2->nom = $lieu->getNom();
            $lieu2->rue = $lieu->getRue();
            $lieu2->longitude = $lieu->getLongitude();
            $lieu2->latitude = $lieu->getLatitude();
            $lieuxArray[] = $lieu2;
        }
        return new JsonResponse($lieuxArray);
    }

    #[Route('/get-ville-by-lieu/{lieuId}', name: 'get_ville_by_lieu')]
    public function getVilleByLieu(int $lieuId, LieuRepository $lieuRepository): JsonResponse
    {
        $res = $lieuRepository->findOneById($lieuId);
        return new JsonResponse($res->getVille()->getId());
    }

    #[Route('/get-lieu-details/{id}', name: 'get_ville_by_id')]
    public function getLieuById(int $id, LieuRepository $lieuRepository): JsonResponse
    {
        $lieu = [];
        $lieuRes = $lieuRepository->findOneById(['id' => $id]);
        $lieuStd = new \stdClass();
        $lieuStd->id = $lieuRes->getId();
        $lieuStd->nom = $lieuRes->getNom();
        $lieuStd->rue = $lieuRes->getRue();
        $lieuStd->longitude = $lieuRes->getLongitude();
        $lieuStd->latitude = $lieuRes->getLatitude();
        $lieu = $lieuStd;
        return new JsonResponse($lieu);
    }

    #[Route('/get-ville-cp/{id}', name: 'get_ville_cp')]
    public function getVilleById(int $id, VilleRepository $villeRepository): JsonResponse
    {
        $ville = [];
        $res = $villeRepository->findOneById(['id' => $id]);
        $std = new \stdClass();
        $std->id = $res->getId();
        $std->nom = $res->getNom();
        $std->cp = $res->getCodePostal();
        $ville = $std;
        return new JsonResponse($ville);
    }
}