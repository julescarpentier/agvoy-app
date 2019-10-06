<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use App\Entity\Region;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
//    public const IDF_REGION_REFERENCE = 'idf-region';

    public function load(ObjectManager $manager)
    {
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Ile de France");
        $region->setPresentation("La région française capitale");
        $manager->persist($region);

        $manager->flush();

        $owner = new Owner();
        $owner->setFirstname("Marie");
        $owner->setFamilyname("Reinbigler");
        $owner->setCountry("FR");

//        $this->addReference(self::IDF_REGION_REFERENCE, $region);

        $room = new Room();
        $room->setSummary("Beau poulailler ancien à Évry");
        $room->setDescription("très joli espace sur paille");
        $room->setCapacity(2);
        $room->setSuperficy(19.43);
        $room->setPrice(3000);
        $room->setAddress("C'est très l'adresse postale");
        $room->addRegion($region);
//        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));
        $room->setOwner($owner);
        $manager->persist($room);

        $manager->flush();
    }
}
