<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CancelSortieType;
use App\Form\FilterSortieType;
use App\Form\SortieType;
use App\Repository\SiteRepository;
use App\Entity\Lieu;
use App\Form\LieuType;
use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index')]
    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository, Request $request): Response
    {
        $user = $this->getUser();
        $filteredSorties = [];

        $form = $this->createForm(FilterSortieType::class);
        $form->handleRequest($request);


        if($form->isSubmitted()) {
            $filterData = $form->getData();
            $filteredSorties = $sortieRepository->findFilteredSortie($filterData);
        }

        return $this->render('sortie/index.html.twig', [
            'sorties' => $filteredSorties,
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $lieu = new Lieu();
        $ville = new Ville();
        $user = $this->getUser();
        $form = $this->createForm(SortieType::class, $sortie);
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formVille = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('publish')->isClicked()) {
                $sortie->setEtat($entityManager->getReference('App\Entity\Etat', 2));
                $this->addFlash('success', 'La sortie à été publié');
            } else {
                $sortie->setEtat($entityManager->getReference('App\Entity\Etat', 1));
                $this->addFlash('success', 'La sortie à été enregistré');
            }

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        /*
        if ($formLieu->isSubmitted() && $formLieu->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu à été ajouter.');
            return $this->redirectToRoute('app_sortie_new', [], Response::HTTP_SEE_OTHER);
        }

        if ($formVille->isSubmitted() && $formVille->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'La ville à été ajouter.');
            return $this->redirectToRoute('app_sortie_new', [], Response::HTTP_SEE_OTHER);
        }
        */

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formLieu' => $formLieu,
            'formVille' => $formVille,
            'user' => $user,
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
        $formLieu = $this->createForm(LieuType::class);
        $formLieu->handleRequest($request);
        $formVille= $this->createForm(VilleType::class);
        $formVille->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formLieu' => $formLieu,
            'formVille' => $formVille,
        ]);
    }

    #[Route('/{id}/register', name: 'app_sortie_register', methods: ['POST'])]
    public function register(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $sortie->addParticipant($user);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/unregister', name: 'app_sortie_unregister', methods: ['POST'])]
    public function unregister(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $sortie->removeParticipant($user);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/publish', name: 'app_sortie_publish', methods: ['POST'])]
    public function publish(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $sortie->setEtat($entityManager->getReference('App\Entity\Etat', 2));
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/cancel', name: 'app_sortie_cancel', methods: ['POST'])]
    public function cancel(Sortie $sortie, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CancelSortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('submit')->isClicked()) {
                $sortie->setEtat($entityManager->getReference('App\Entity\Etat', 6));
                $sortie->setInfosSortie($form->get('infosSortie')->getData());
                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
            } elseif ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('sortie/cancelSortie.html.twig', [
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
