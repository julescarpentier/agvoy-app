<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class CommentaireFixtures extends Fixture
{
    public const MR_COMMENT_REFERENCE = 'mr-comment';
    public const JC_COMMENT_REFERENCE = 'jc-comment';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getCommentaireData() as [$avis, $auteur, $reference]) {
            $commentaire = new Commentaire();
            $commentaire->setCommentaire($avis);
            $commentaire->setAuteur($auteur);
            $manager->persist($commentaire);

            $this->addReference($reference, $commentaire);
        }

        $manager->flush();
    }

    private function getCommentaireData()
    {
        yield ["Endroit atypique mais très agréable. Personnel sympatique et accueillant.", $this->getReference(ClientFixtures::MR_CLIENT_REFERENCE), self::MR_COMMENT_REFERENCE];
        yield ["A éviter ! Personnel anthipatique et chambre minuscule.", $this->getReference(ClientFixtures::JC_CLIENT_REFERENCE), self::JC_COMMENT_REFERENCE];
    }
    
    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
        );
    }
}
