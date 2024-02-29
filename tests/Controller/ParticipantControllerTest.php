<?php

namespace App\Test\Controller;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipantControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/participant/';

    private function getRepository(): EntityRepository
    {
        return $this->manager->getRepository(Participant::class);
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'sortie[nom]' => 'Testing',
            'sortie[dateHeureDebut]' => 'Testing',
            'sortie[duree]' => 'Testing',
            'sortie[dateLimiteInscription]' => 'Testing',
            'sortie[nbInscriptionsMax]' => 'Testing',
            'sortie[infosSortie]' => 'Testing',
            'sortie[etat]' => 'Testing',
            'sortie[organisateur]' => 'Testing',
            'sortie[site]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }
}