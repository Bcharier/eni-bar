<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csvFile')->getData();
            if ($csvFile instanceof UploadedFile) {
                // On récupère le chemin temporaire du fichier
                $tempFilePath = $csvFile->getRealPath();

                // Traitement du fichier
                $csvData = $this->readCsvFile($tempFilePath);

                // Import des utilisateurs
                $this->importUsers($csvData);

                // Message flash pour informer de l'importation réussie
                $this->addFlash('success', 'Users imported successfully.');
            }
            $user->setAdministrateur(0);
            $user->setActif(1);

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre inscription à bien été faite!');
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        } else if ($form->isSubmitted()) 
        {
            $this->addFlash('error', 'Erreur...');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function readCsvFile(string $filePath): array
    {
        // Lire le contenu du fichier CSV
        $csvData = file_get_contents($filePath);

        // Créer un objet Serializer avec l'encodeur CSV
        
        $serializer = new Serializer( [new ObjectNormalizer()], [new CsvEncoder(['delimiter' => ";"])]);
        // Décoder le contenu CSV en un tableau associatif
        $decodedData = $serializer->decode($csvData, 'csv');
        
        $participants = [];

        // Assurez-vous que le tableau $decodedData n'est pas vide
        if (!empty($decodedData)) {
            foreach ($decodedData as $row) {
                // Désérialisez chaque ligne du CSV en un objet Participant
                $participant = $serializer->deserialize($row["mail;nom;prenom;pseudo;site;password"], Participant::class, 'csv');
                
                // Ajoutez l'objet Participant au tableau
                $participants[] = $participant;
            }
        }
        return $participants;
    }

    private function importUsers(array $csvData): array
    {
        $importedUsers = [];

        foreach ($csvData as $userData) {
            // Créez un nouvel utilisateur en utilisant les données CSV
            $user = new Participant();
            $user->setMail($userData['Mail']);
            $user->setNom($userData['Nom']);
            $user->setPrenom($userData['Prenom']);
            $user->setPseudo($userData['Pseudo']);
            $user->setSite($userData['Site']);
            $user->setPassword(password_hash($userData['Password'], PASSWORD_BCRYPT));
            // Ajoutez l'utilisateur à la liste des utilisateurs importés
            $importedUsers[] = $user;
        }
        return $importedUsers;
    }
}
