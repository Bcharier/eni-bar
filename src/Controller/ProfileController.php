<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = new Participant();
        // $user = $this->getUser();
        $user = $em->getRepository(Participant::class)->find(1);

        $form = $this->createForm(ParticipantType::class, $user);

        // dire au formulaire de gérer la requête
        $form->handleRequest($request);
    
        // Si le formulaire a déjà été soumis
        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre profil a bien été mis à jour !');

            $em->flush();
        } else {
            // $this->addFlash('info', 'Formulaire neuf');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'current_user' => $user,
            'form' => $form,
        ]);
    }
}
