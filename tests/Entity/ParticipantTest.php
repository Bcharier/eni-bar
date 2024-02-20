<?php

namespace App\Tests\Entity;

use App\Entity\Participant;
use PHPUnit\Framework\TestCase;

class ParticipantTest extends TestCase
{
    public function testParticipant()
    {
        $participant = new Participant();
        $nom = "La Banane";
        $prenom = "Jojo";
        
        $participant->setNom($nom);
        $participant->setPrenom($prenom);
        $this->assertEquals("Jojo", $participant->getPrenom());
        $this->assertEquals("La Banane", $participant->getNom());
    }
}