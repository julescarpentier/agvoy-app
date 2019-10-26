<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Indisponibilite;
use App\Entity\Owner;
use App\Entity\Region;
use App\Entity\Reservation;
use App\Entity\Room;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Regions

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Bretagne");
        $region->setPresentation("Située à l’extrême ouest de la France, est une péninsule vallonnée qui s’avance dans l’océan Atlantique.");
        $manager->persist($region);

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Pays de la Loire");
        $region->setPresentation("Elle comprend une partie de la vallée de la Loire, renommée pour ses vignobles.");
        $manager->persist($region);

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Normandie");
        $region->setPresentation("Son littoral varié comprend des falaises de craie blanche et des têtes de pont de la Seconde Guerre mondiale");
        $manager->persist($region);

        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Ile de France");
        $region->setPresentation("Elle entoure la célèbre capitale du pays, Paris, centre international de culture et de gastronomie avec ses cafés chics et ses jardins structurés.");
        $manager->persist($region);

        // Owners

        $owner = new Owner();
        $owner->setFirstname("Marie");
        $owner->setFamilyname("Reinbigler");
        $owner->setCountry("FR");
        $manager->persist($owner);

        $client = new Client();
        $client->setFirstname("Jules");
        $client->setFamilyname("Carpentier");
        $manager->persist($client);

        $reservation = new Reservation();
        $reservation->setDateEntree(new DateTime("10/10/2019"));
        $reservation->setDuree(5);
        $reservation->setClient($client);
        $manager->persist($reservation);

        $indisponibilite = new Indisponibilite();
        $indisponibilite->setDateDebut(new DateTime("12/24/2019"));
        $indisponibilite->setDateFin(new DateTime("12/26/2019"));
        $manager->persist($indisponibilite);

        // Rooms

        $room = new Room();
        $room->setSummary("Beau poulailler ancien à Évry");
        $room->setDescription("très joli espace sur paille");
        $room->setCapacity(2);
        $room->setSuperficy(19.43);
        $room->setPrice(3000);
        $room->setAddress("9 Rue Charles Fourier, 91228 EVRY");
        $room->addRegion($region);
        $room->setOwner($owner);
        $room->addReservation($reservation);
        $room->addDisponibilite($indisponibilite);
        $manager->persist($room);

        $room = new Room();
        $room->setSummary("Donjon rénové à Courneuve");
        $room->setDescription("belle vue à travers les meurtières");
        $room->setCapacity(2);
        $room->setSuperficy(8.64);
        $room->setPrice(1500);
        $room->setAddress("71, rue des Soeurs, 93120 LA COURNEUVE");
        $room->addRegion($region);
        $room->setOwner($owner);
        $room->addReservation($reservation);
        $room->addDisponibilite($indisponibilite);
        $manager->persist($room);

        $manager->flush();
    }
}
