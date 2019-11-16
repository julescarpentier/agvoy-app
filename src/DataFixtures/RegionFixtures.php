<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public const BRE_REGION_REFERENCE = 'bre-region';
    public const PDL_REGION_REFERENCE = 'pdl-region';
    public const NOR_REGION_REFERENCE = 'nor-region';
    public const IDF_REGION_REFERENCE = 'idf-region';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getRegionData() as [$country, $name, $presentation, $reference]) {
            $region = new Region();
            $region->setCountry($country);
            $region->setName($name);
            $region->setPresentation($presentation);
            $manager->persist($region);

            $this->addReference($reference, $region);
        }

        $manager->flush();
    }

    private function getRegionData()
    {
        yield [
            "FR",
            "Bretagne",
            "Située à l’extrême ouest de la France, est une péninsule vallonnée qui s’avance dans l’océan Atlantique.",
            self::BRE_REGION_REFERENCE,
        ];

        yield [
            "FR",
            "Pays de la Loire",
            "Elle comprend une partie de la vallée de la Loire, renommée pour ses vignobles.",
            self::PDL_REGION_REFERENCE,
        ];

        yield [
            "FR",
            "Normandie",
            "Son littoral varié comprend des falaises de craie blanche et des têtes de pont de la Seconde Guerre mondiale",
            self::NOR_REGION_REFERENCE,
        ];

        yield [
            "FR",
            "Ile de France",
            "Elle entoure la célèbre capitale du pays, Paris, centre international de culture et de gastronomie avec ses cafés chics et ses jardins structurés.",
            self::IDF_REGION_REFERENCE,
        ];
    }
}