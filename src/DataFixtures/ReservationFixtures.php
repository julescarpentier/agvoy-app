<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ReservationFixtures extends Fixture
{
    public const RESERVATION_1_REFERENCE = 'reservation-1';
    public const RESERVATION_2_REFERENCE = 'reservation-2';

    public function load(ObjectManager $manager)
    {
        $reservation = new Reservation();
        $reservation->setDateEntree(new DateTime("10/10/2019"));
        $reservation->setDuree(5);
        $reservation->setClient($this->getReference(ClientFixtures::JC_CLIENT_REFERENCE));
        $manager->persist($reservation);

        $this->addReference(self::RESERVATION_1_REFERENCE, $reservation);

        $reservation = new Reservation();
        $reservation->setDateEntree(new DateTime("06/15/2020"));
        $reservation->setDuree(7);
        $reservation->setClient($this->getReference(ClientFixtures::JC_CLIENT_REFERENCE));
        $manager->persist($reservation);

        $this->addReference(self::RESERVATION_2_REFERENCE, $reservation);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
        );
    }
}
