<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        //$user = new Participant();
        $user = $this->getUser();

        $user = $em->getRepository(Participant::class)->findOneByMail($user->getUserIdentifier());

        $form = $this->createForm(ParticipantType::class, $user);

        // dire au formulaire de gérer la requête
        $form->handleRequest($request);
    
        // Si le formulaire a déjà été soumis
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $this->addFlash('success', 'Votre profil a bien été mis à jour !');

            $em->flush();
        } else {
            // $this->addFlash('info', 'Formulaire neuf');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/profile/{id}', name: 'app_profile_viewer', requirements:['id' => '\d+'])]
    public function profileViewer(string $id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $viewed_user = $em->getRepository(Participant::class)->findOneById($id);
        return $this->render('profile/profile-viewer.html.twig', [
            'controller_name' => 'ProfileController',
            'viewed_user' => $viewed_user,
            'user' => $user,
        ]);
    }
}
