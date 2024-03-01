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
use App\Service\UpdateStateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index')]
    public function sorties(SortieRepository $sortieRepository, Request $request): Response
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

    #[Route('/uos', name: 'app_sortie_update_ongoing', methods: ['GET'])]
    public function updateOngoingSorties(UpdateStateService $updateStateService): Response
    {
        $res = $updateStateService->updateOngoingSorties();

        $this->addFlash('success', 'L`état des sorties est mis à jour');
        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
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

            $sortie->setOrganisateur($this->getUser());
            $sortie->setSite($this->getUser()->getSite());
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

        return $this->render('sortie/new.html.twig', [
            'form' => $form,
            'formLieu' => $formLieu,
            'formVille' => $formVille,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/showSortie.html.twig', [
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

        if ($form->isSubmitted() && $form->get('delete')->isClicked()) {
            return $this->redirectToRoute('app_sortie_delete', ['id' => $sortie->getId()], 307);
        }

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
        $form = $this->createForm(CancelSortieType::class, $sortie);
        $form->handleRequest($request);

        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/get/{id}', name: 'app_api_sortie', methods: ['GET'])]
    public function getSortieById(Sortie $sortie): JsonResponse
    {
        $participants = $sortie->getParticipants();
        $participantsArray = [];

        foreach($participants as $participant) {
            $participantsArray[] = array('id' => $participant->getId(),
                                        'nom' => $participant->getNom(),
                                        'prenom' => $participant->getPrenom(),
                                        'site' => $participant->getSite()->getNom());
        }

        $ville = array('id' => $sortie->getLieu()->getVille()->getId(),
                        'nom' => $sortie->getLieu()->getVille()->getNom(),
                        'codePostal' => $sortie->getLieu()->getVille()->getCodePostal());

        $lieu = array('id' => $sortie->getLieu()->getId(),
                        'nom' => $sortie->getLieu()->getNom(),
                        'rue' => $sortie->getLieu()->getRue(),
                        'latitude' => $sortie->getLieu()->getLatitude(),
                        'longitude' => $sortie->getLieu()->getLongitude());
        
        $output = array('id' => $sortie->getId(),
                        'nom' => $sortie->getNom(),
                        'dateHeureDebut' => $sortie->getDateHeureDebut()->format('d-m-Y H:i:s'),
                        'duree' => $sortie->getDuree(),
                        'limite' => $sortie->getDateLimiteInscription()->format('d-m-Y H:i:s'),
                        'nbInscriptionsMax' => $sortie->getNbInscriptionsMax(),
                        'infosSortie' => $sortie->getInfosSortie(),
                        'etat' => $sortie->getEtat()->getLibelle(),
                        'lieu' => $lieu,
                        'ville' => $ville,
                        'organisateur' => $sortie->getOrganisateur()->getNom(),
                        'site' => $sortie->getSite()->getNom(),
                        'participants' => $participantsArray);

        return new JsonResponse($output);
    }
}
