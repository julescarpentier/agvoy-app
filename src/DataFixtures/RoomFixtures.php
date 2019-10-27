<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $room = new Room();
        $room->setSummary("Beau poulailler ancien à Évry");
        $room->setDescription("très joli espace sur paille");
        $room->setCapacity(2);
        $room->setSuperficy(19.43);
        $room->setPrice(3000);
        $room->setAddress("9 Rue Charles Fourier, 91228 EVRY");
        $room->addRegion($this->getReference(RegionFixtures::IDF_REGION_REFERENCE));
        $room->setOwner($this->getReference(OwnerFixtures::MR_OWNER_REFERENCE));
        $room->addReservation($this->getReference(ReservationFixtures::RESERVATION_1_REFERENCE));
        $room->addDisponibilite($this->getReference(IndisponibliteFixtures::INDISPONIBILITE_REFERENCE));
        $manager->persist($room);

        $room = new Room();
        $room->setSummary("Donjon rénové à La Courneuve");
        $room->setDescription("belle vue à travers les meurtières");
        $room->setCapacity(2);
        $room->setSuperficy(8.64);
        $room->setPrice(1500);
        $room->setAddress("71, rue des Soeurs, 93120 LA COURNEUVE");
        $room->addRegion($this->getReference(RegionFixtures::IDF_REGION_REFERENCE));
        $room->setOwner($this->getReference(OwnerFixtures::MR_OWNER_REFERENCE));
        $room->addReservation($this->getReference(ReservationFixtures::RESERVATION_2_REFERENCE));
        $room->addDisponibilite($this->getReference(IndisponibliteFixtures::INDISPONIBILITE_REFERENCE));
        $manager->persist($room);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            RegionFixtures::class,
            OwnerFixtures::class,
            ReservationFixtures::class,
            IndisponibliteFixtures::class,
        );
    }
}
