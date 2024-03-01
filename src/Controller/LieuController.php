<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/lieu')]
class LieuController extends AbstractController
{
    #[Route('/', name: 'app_lieu_index', methods: ['GET'])]
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lieu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_show', methods: ['GET'])]
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lieu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_delete', methods: ['POST'])]
    public function delete(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lieu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/api/get/{ville_id}', name: 'app_api_lieu', methods: ['GET'])]
    public function api(LieuRepository $lieuRepository, SerializerInterface $serializer, $ville_id): JsonResponse
    {
        $output = [];
        $lieux = $lieuRepository->findByVille($ville_id);
        foreach ($lieux as $lieu){
            if($lieu->getId() > 0) {
            $output[]=array($lieu->getId(),$lieu->getNom(), $lieu->getVille()->getNom());
            }
        }
        return  new JsonResponse($output);
    }

    #[Route('/api/post/newLieu', name: 'app_api_newLieu')]
    public function insertNewLieu(EntityManagerInterface $entityManager) : Response
    {
        $lieu = new Lieu();
        $lieu->setNom($_POST['nom']);
        $lieu->setRue($_POST['rue']);
        $lieu->setLatitude($_POST['latitude']);
        $lieu->setLongitude($_POST['longitude']);
        $lieu->setVille($entityManager->getReference('App\Entity\Ville', $_POST['ville']));
        $entityManager->persist($lieu);
        $entityManager->flush();

        return new Response();
    }

    #[Route('/api/get/lieuByVilleId/{ville}', name: 'app_api_lieu', methods: ['GET'])]
    public function getLieuByVilleId(LieuRepository $lieuRepository, $ville): JsonResponse
    {
        $lieux = $lieuRepository->findByVille($ville);

        return new JsonResponse(json_encode($lieux));
    }
}
