<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]
    public function sorties(SortieRepository $sortieRepository): Response
    {

        $allSorties = $sortieRepository->findAllSorties();

        return $this->render('sorties/index.html.twig', [

            'sorties' => $allSorties,
        ]);
    }
}
