<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OwnerFixtures extends Fixture
{
    public const MR_OWNER_REFERENCE = 'mr-owner';

    public function load(ObjectManager $manager)
    {
        $owner = new Owner();
        $owner->setFirstname("Marie");
        $owner->setFamilyname("Reinbigler");
        $owner->setCountry("FR");
        $manager->persist($owner);

        $this->addReference(self::MR_OWNER_REFERENCE, $owner);

        $manager->flush();
    }
}
