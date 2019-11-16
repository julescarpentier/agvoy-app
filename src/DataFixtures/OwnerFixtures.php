<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OwnerFixtures extends Fixture
{
    public const MR_OWNER_REFERENCE = 'mr-owner';
    public const JC_OWNER_REFERENCE = 'js-owner';
    public const OB_OWNER_REFERENCE = 'ob-owner';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getOwnerData() as [$country, $reference]) {
            $owner = new Owner();
            $owner->setCountry($country);
            $manager->persist($owner);

            $this->addReference($reference, $owner);
        }

        $manager->flush();
    }

    private function getOwnerData()
    {
        yield ["FR", self::MR_OWNER_REFERENCE];
        yield ["FR", self::JC_OWNER_REFERENCE];
        yield ["FR", self::OB_OWNER_REFERENCE];
    }
}
