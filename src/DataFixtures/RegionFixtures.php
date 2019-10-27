<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RegionFixtures extends Fixture
{
    public const BRE_REGION_REFERENCE = 'bre-region';
    public const IDF_REGION_REFERENCE = 'idf-region';

    public function load(ObjectManager $manager)
    {
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Bretagne");
        $region->setPresentation("Située à l’extrême ouest de la France, est une péninsule vallonnée qui s’avance dans l’océan Atlantique.");
        $manager->persist($region);

        $this->addReference(self::BRE_REGION_REFERENCE, $region);

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

        $this->addReference(self::IDF_REGION_REFERENCE, $region);

        $manager->flush();
    }
}
