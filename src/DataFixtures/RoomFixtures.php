<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getRoomData() as [$summary, $description, $capacity, $surface, $price, $address, $region, $owner, $reservation, $disponibilite]) {
            $room = new Room();
            $room->setSummary($summary);
            $room->setDescription($description);
            $room->setCapacity($capacity);
            $room->setSurface($surface);
            $room->setPrice($price);
            $room->setAddress($address);
            $room->addRegion($region);
            $room->setOwner($owner);
            $room->addReservation($reservation);
            $room->addIndisponibilite($disponibilite);
            $manager->persist($room);
        }

        $manager->flush();
    }

    private function getRoomData()
    {
        yield [
            "Beau poulailler ancien à Évry",
            "très joli espace sur paille",
            2,
            19.43,
            3000,
            "9 Rue Charles Fourier, 91228 EVRY",
            $this->getReference(RegionFixtures::IDF_REGION_REFERENCE),
            $this->getReference(OwnerFixtures::MR_OWNER_REFERENCE),
            $this->getReference(ReservationFixtures::RESERVATION_1_REFERENCE),
            $this->getReference(IndisponibliteFixtures::INDISPONIBILITE_REFERENCE),
        ];

        yield [
            "Donjon rénové à La Courneuve",
            "belle vue à travers les meurtières",
            2,
            8.64,
            1500,
            "71, rue des Soeurs, 93120 LA COURNEUVE",
            $this->getReference(RegionFixtures::IDF_REGION_REFERENCE),
            $this->getReference(OwnerFixtures::MR_OWNER_REFERENCE),
            $this->getReference(ReservationFixtures::RESERVATION_2_REFERENCE),
            $this->getReference(IndisponibliteFixtures::INDISPONIBILITE_REFERENCE),
        ];
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
