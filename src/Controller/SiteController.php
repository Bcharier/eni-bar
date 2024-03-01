<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/site')]
class SiteController extends AbstractController
{
    #[Route('/', name: 'app_site_index', methods: ['POST', 'GET'])]
    public function index(Request $request, SiteRepository $siteRepository): Response
    {
        if($request->request->get('name-filter') && trim($request->request->get('name-filter') != "")) {
            return $this->render('site/index.html.twig', [
                'sites' => $siteRepository->findSitesThatcontains($request->request->get('name-filter')),
                'search' => $request->request->get('name-filter'),
            ]);
          } else {
            return $this->render('site/index.html.twig', [
                'sites' => $siteRepository->findAll(),
            ]);
          }
    }

    #[Route('/new', name: 'app_site_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $site->getNom();
            $entityManager->persist($site);
            $entityManager->flush();
            $this->addFlash('success', 'Le site "'. $name .'" a bien été ajouté !');

            return $this->redirectToRoute('app_site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('site/new.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_site_show', methods: ['GET'])]
    public function show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'site' => $site,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_site_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La ville "'. $site->getNom() .'" a bien été modifié !');

            return $this->redirectToRoute('app_site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('site/edit.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_site_delete', methods: ['POST'])]
    public function delete(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_site_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}', name: 'app_site_delete_by_id', methods: ['POST'])]
    public function deleteById(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token')) && $site->getSortie()[0] == null) {
            $name = $site->getNom();
            $entityManager->remove($site);
            $entityManager->flush();
            $this->addFlash('success', 'La ville "'. $name .'" a bien été supprimée !');
        } else {
            $this->addFlash('error', 'Vous n`avez pas le droit de faire cela !');
        }

        return $this->redirectToRoute('app_site_index', [], Response::HTTP_SEE_OTHER);
    }
}
