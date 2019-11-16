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
        foreach ($this->getReservationData() as [$date_entree, $duree, $client, $reference]) {
            $reservation = new Reservation();
            $reservation->setDateEntree($date_entree);
            $reservation->setDuree($duree);
            $reservation->setClient($client);
            $manager->persist($reservation);

            $this->addReference($reference, $reservation);
        }

        $manager->flush();
    }

    private function getReservationData()
    {
        yield [new DateTime("10/10/2019"), 5, $this->getReference(ClientFixtures::JC_CLIENT_REFERENCE), self::RESERVATION_1_REFERENCE];
        yield [new DateTime("06/15/2020"), 7, $this->getReference(ClientFixtures::MS_CLIENT_REFERENCE), self::RESERVATION_2_REFERENCE];
    }

    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
        );
    }
}
