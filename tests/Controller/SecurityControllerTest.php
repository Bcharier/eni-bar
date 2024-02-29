<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Participant;

class SecurityControllerTest extends WebTestCase
{
    public function testForgottenPassword(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        // Supprimer les utilisateurs existants pour commencer avec une base propre
        $users = $entityManager->getRepository(Participant::class)->findAll();
        foreach ($users as $user) {
            $entityManager->remove($user);
        }
        $entityManager->flush();

        // Créer un utilisateur de test
        $user = new Participant();
        $user->setMail('test@example.com');
        $entityManager->persist($user);
        $entityManager->flush();

        $client->request('GET', '/password_reset');

        // Remplissez le formulaire avec l'email de l'utilisateur de test
        $client->submitForm('submit', [
            'reset_password_request_form[mail]' => 'test@example.com',
        ]);

        // Vérifiez que la réponse est une redirection
        $this->assertResponseRedirects('/login');

        // Vérifiez que l'email a été envoyé avec succès (vous devrez ajuster cela en fonction de votre implémentation)
        $this->assertEmailCount(1);
        $this->assertEmailHeaderSame(0, 'Subject', 'Réinitialisation de mot de passe');

        // Vérifiez le contenu de l'email si nécessaire
        $email = $this->getMailerMessage(0);
        $emailContent = $email->getBody();

        // Vérifiez que le token a été généré et sauvegardé dans la base de données
        $user = $entityManager->getRepository(Participant::class)->findOneBy(['mail' => 'test@example.com']);
        $this->assertNotNull($user);
        $this->assertNotEmpty($user->getResetToken());

        // Vérifiez que le flash message a été défini
        $crawler = $client->followRedirect();
        $flashMessage = $crawler->filter('.flash-message')->text();
        $this->assertEquals('Email envoyé avec succès', trim($flashMessage));
    }
}
