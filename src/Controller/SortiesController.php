<?php

namespace App\Controller;

use App\Form\FilterSortieType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]
    public function sorties(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();
        $filteredSorties = [];

        $form = $this->createForm(FilterSortieType::class);
        $form->handleRequest($request);


        if($form->isSubmitted()) {
            $filterData = $form->getData();
            $filteredSorties = $sortieRepository->findFilteredSorties($filterData);
        }

        return $this->render('sorties/index.html.twig', [
            'sorties' => $filteredSorties,
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/showSortie', name: 'app_showSortie')]
    public function showSortie(SortieRepository $sortieRepository): Response
    {
        $request = Request::createFromGlobals();
        $sortieId = $request->query->get('id');
        $sortie = $sortieRepository->find($sortieId);

        return $this->render('sorties/showSortie.html.twig', [
            'sortie' => $sortie
        ]);
    }
}
