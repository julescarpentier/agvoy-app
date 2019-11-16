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
        foreach ($this->getIndisponibiliteData() as [$date_debut, $date_fin, $reference]) {
            $indisponibilite = new Indisponibilite();
            $indisponibilite->setDateDebut($date_debut);
            $indisponibilite->setDateFin($date_fin);
            $manager->persist($indisponibilite);

            $this->addReference($reference, $indisponibilite);
        }

        $manager->flush();
    }

    private function getIndisponibiliteData()
    {
        yield [new DateTime("12/24/2019"), new DateTime("12/26/2019"), self::INDISPONIBILITE_REFERENCE];
    }
}
