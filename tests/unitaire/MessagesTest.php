<?php

namespace App\Tests\Unit;

use App\Entity\Utilisateur;
use App\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UtilisateurTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAddAndRemoveSentMessages()
    {
        $user = new Utilisateur();
        $message = new Messages();

        // Test adding a sent message
        $user->addSent($message);
        $this->assertCount(1, $user->getSent());
        $this->assertSame($message, $user->getSent()[0]);
        $this->assertSame($user, $message->getExpediteur());

        // Test removing a sent message
        $user->removeSent($message);
        $this->assertCount(0, $user->getSent());
        $this->assertNull($message->getExpediteur());
    }

    public function testAddAndRemoveReceivedMessages()
    {
        $user = new Utilisateur();
        $message = new Messages();

        // Test adding a received message
        $user->addRecu($message);
        $this->assertCount(1, $user->getRecu());
        $this->assertSame($message, $user->getRecu()[0]);
        $this->assertSame($user, $message->getDestinataire());

        // Test removing a received message
        $user->removeRecu($message);
        $this->assertCount(0, $user->getRecu());
        $this->assertNull($message->getDestinataire());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
