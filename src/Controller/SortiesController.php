<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'app_sorties')]
    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository): Response
    {
        $user = $this->getUser();

        $filteredSorties = [];

        if(isset($_GET['filterData'])) {
            $filterData = $_GET['filterData'];
            $filteredSorties = $sortieRepository->findFilteredSorties($filterData);
        }

        $allSites = $siteRepository->findAllSites();

        return $this->render('sorties/index.html.twig', [

            'sorties' => $filteredSorties,
            'sites' => $allSites,
            'user' => $user,
        ]);
    }

    #[Route('/filterSorties', name: 'app_filterSorties', methods: 'POST')]
    public function filterSorties(): Response
    {
        $request = Request::createFromGlobals();
        $site = $request->request->get('sites');
        $nameSearch = $request->request->get('name-search');
        $dateStart = $request->request->get('date-start');
        $dateEnd = $request->request->get('date-end');
        $organizer = $request->request->get('checkbox-organizer') != null ? $this->getUser()->getId() : null;
        $registered = $request->request->get('checkbox-registered') != null ? $this->getUser()->getId() : null;
        $notRegistered = $request->request->get('checkbox-not-registered') != null ? $this->getUser()->getId() : null;
        $passed = $request->request->get('checkbox-past') != null ? true : false;

        $filterData = [
            'site' => $site,
            'nameSearch' => $nameSearch,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'organizer' => $organizer,
            'registered' => $registered,
            'notRegistered' => $notRegistered,
            'passed' => $passed
        ];
        
        return $this->redirectToRoute('app_sorties', [
            'filterData' => $filterData
        ]);
    }
}
