<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public const JC_CLIENT_REFERENCE = 'jc-client';

    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setFirstname("Jules");
        $client->setFamilyname("Carpentier");
        $manager->persist($client);

        $this->addReference(self::JC_CLIENT_REFERENCE, $client);

        $manager->flush();
    }
}
