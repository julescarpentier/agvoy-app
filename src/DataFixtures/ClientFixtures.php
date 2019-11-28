<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public const MR_CLIENT_REFERENCE = 'mr-client';
    public const JC_CLIENT_REFERENCE = 'jc-client';
    public const OB_CLIENT_REFERENCE = 'ob-client';
    public const MS_CLIENT_REFERENCE = 'ms-client';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getClientData() as [$reference]) {
            $client = new Client();
            $manager->persist($client);

            $this->addReference($reference, $client);
        }

        $manager->flush();
    }

    private function getClientData()
    {
        yield [self::MR_CLIENT_REFERENCE];
        yield [self::JC_CLIENT_REFERENCE];
        yield [self::OB_CLIENT_REFERENCE];
        yield [self::MS_CLIENT_REFERENCE];
    }
}
