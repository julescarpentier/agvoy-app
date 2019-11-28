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
        foreach ($this->getReservationData() as [$dateEntree, $dateSortie, $client, $reference]) {
            $reservation = new Reservation();
            $reservation->setDateEntree($dateEntree);
            $reservation->setDateSortie($dateSortie);
            $reservation->setClient($client);
            $manager->persist($reservation);

            $this->addReference($reference, $reservation);
        }

        $manager->flush();
    }

    private function getReservationData()
    {
        yield [new DateTime("10/10/2019"), new DateTime("10/20/2019"), $this->getReference(ClientFixtures::JC_CLIENT_REFERENCE), self::RESERVATION_1_REFERENCE];
        yield [new DateTime("06/15/2020"), new DateTime("10/01/2020"), $this->getReference(ClientFixtures::MS_CLIENT_REFERENCE), self::RESERVATION_2_REFERENCE];
    }

    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
        );
    }
}
