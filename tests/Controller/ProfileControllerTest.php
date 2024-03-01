<?php

namespace App\Tests\Controller;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{

    public function testVisitingWhileLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(ParticipantRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByMail('georges@eni.fr');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello John!');
    }
}