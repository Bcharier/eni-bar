<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\SiteRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager, SiteRepository $siteRepository): Response
    {
        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csvFile')->getData();
            if ($csvFile instanceof UploadedFile) {
                // Traitement du fichier
                $csvData = $this->readCsvFile($csvFile);

                // Import des utilisateurs
                $importedUsers = $this->importUsers($csvData, $siteRepository);
                
                foreach ($importedUsers as $user) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                }

                $this->addFlash('success', 'L\'importation a été effectuée');
            }
            $user->setAdministrateur(0);
            $user->setActif(1);

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre inscription à bien été faite!');

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        } else if ($form->isSubmitted()) {
            $this->addFlash('error', 'Erreur...');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function readCsvFile(UploadedFile $filePath)
    {
        try {
            $csv = [];
            if (($handle = fopen($filePath->getPathname(), "r")) !== false) {
                $headers = fgetcsv($handle);
                $headers = array_map('trim', $headers);
                while (($data = fgetcsv($handle)) !== false) {
                    $csv [] = $data;
                }
                fclose($handle);
            }
            return $csv;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function importUsers(array $csvData, SiteRepository $siteRepository): array
    {
        $importedUsers = [];
        
        foreach ($csvData as $userData) {
            // Créez un nouvel utilisateur en utilisant les données CSV
            $site = $siteRepository->find($userData[4]);

            $user = new Participant();
            $user->setMail($userData[0]);
            $user->setNom($userData[1]);
            $user->setPrenom($userData[2]);
            $user->setPseudo($userData[3]);
            $user->setSite($site);
            $user->setPassword(password_hash($userData[5], PASSWORD_BCRYPT));
            $user->setAdministrateur($userData[6]);
            $user->setActif(true);
            // Ajoutez l'utilisateur à la liste des utilisateurs importés
            $importedUsers[] = $user;
        }
        return $importedUsers;
    }
}
