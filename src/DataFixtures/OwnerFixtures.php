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
        foreach ($this->getOwnerData() as [$address, $country, $reference]) {
            $owner = new Owner();
            $owner->setAddress($address);
            $owner->setCountry($country);
            $manager->persist($owner);

            $this->addReference($reference, $owner);
        }

        $manager->flush();
    }

    private function getOwnerData()
    {
        yield ["14 rue Charles Fourier, 91000 Evry", "FR", self::MR_OWNER_REFERENCE];
        yield ["14 rue Charles Fourier, 91000 Evry", "FR", self::JC_OWNER_REFERENCE];
        yield ["9 rue Charles Fourier, 91000 Evry", "FR", self::OB_OWNER_REFERENCE];
    }
}
