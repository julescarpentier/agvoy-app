<?php

namespace App\DataFixtures;

use App\Entity\Indisponibilite;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IndisponibliteFixtures extends Fixture
{
    public const INDISPONIBILITE_REFERENCE = 'indisponibilite';

    public function load(ObjectManager $manager)
    {
        $indisponibilite = new Indisponibilite();
        $indisponibilite->setDateDebut(new DateTime("12/24/2019"));
        $indisponibilite->setDateFin(new DateTime("12/26/2019"));
        $manager->persist($indisponibilite);

        $this->addReference(self::INDISPONIBILITE_REFERENCE, $indisponibilite);

        $manager->flush();
    }
}
