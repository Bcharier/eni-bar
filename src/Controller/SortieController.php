<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Entity\Lieu;
use App\Form\LieuType;
use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\SortieRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    /*
    #[Route('/', name: 'app_sortie_index', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sortie' => $sortieRepository->findAll(),
        ]);
    }
    */

    #[Route('/', name: 'app_sortie_index')]
    public function sortie(SortieRepository $sortieRepository, SiteRepository $siteRepository): Response
    {
        $user = $this->getUser();

        $filteredSortie = [];

        if(isset($_GET['filterData'])) {
            $filterData = $_GET['filterData'];
            $filteredSortie = $sortieRepository->findFilteredSortie($filterData);
        }

        $allSites = $siteRepository->findAllSites();

        return $this->render('sortie/index.html.twig', [

            'sorties' => $filteredSortie,
            'sites' => $allSites,
            'user' => $user,
        ]);
    }

    #[Route('/filterSortie', name: 'app_filterSortie', methods: 'POST')]
    public function filterSortie(): Response
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
        
        return $this->redirectToRoute('app_sortie_index', [
            'filterData' => $filterData
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $lieu = new Lieu();
        $ville = new Ville();
        $form = $this->createForm(SortieType::class, $sortie);
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formVille = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);
        $formLieu->handleRequest($request);
        $formVille->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $sortie->setEtat(1);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($formLieu->isSubmitted() && $formLieu->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu à été ajouter.');
            //return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'La ville à été ajouter.');
            //return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formLieu' => $formLieu,
            'formVille' => $formVille,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }
}
