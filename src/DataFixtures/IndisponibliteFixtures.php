<?php

namespace App\DataFixtures;

use App\Entity\Indisponibilite;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IndisponibliteFixtures extends Fixture
{
    public const INDISPONIBILITE_1_REFERENCE = 'indisponibilite';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getIndisponibiliteData() as [$dateDebut, $dateFin, $reference]) {
            $indisponibilite = new Indisponibilite();
            $indisponibilite->setDateDebut($dateDebut);
            $indisponibilite->setDateFin($dateFin);
            $manager->persist($indisponibilite);

            $this->addReference($reference, $indisponibilite);
        }

        $manager->flush();
    }

    private function getIndisponibiliteData()
    {
        yield [new DateTime("12/24/2019"), new DateTime("12/26/2019"), self::INDISPONIBILITE_1_REFERENCE];
    }
}
