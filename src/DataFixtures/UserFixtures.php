<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email, $firstname, $familyname, $plainPassword, $role, $client, $owner]) {
            $user = new User();
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setFirstname($firstname);
            $user->setFamilyname($familyname);
            $user->setPassword($encodedPassword);
            $user->addRole($role);
            $user->setClient($client);
            $user->setOwner($owner);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData()
    {
        yield ['marie.reinbigler@telecom-sudparis.eu', 'Marie', 'Reinbigler', 'marie', 'ROLE_ADMIN', $this->getReference(ClientFixtures::MR_CLIENT_REFERENCE), $this->getReference(OwnerFixtures::MR_OWNER_REFERENCE)];
        yield ['jules.carpentier@telecom-sudparis.eu', 'Jules', 'Carpentier', 'jules', 'ROLE_ADMIN', $this->getReference(ClientFixtures::JC_CLIENT_REFERENCE), $this->getReference(OwnerFixtures::JC_OWNER_REFERENCE)];
        yield ['olivier.berger@telecom-sudparis.eu', 'Olivier', 'Berger', 'olivier', 'ROLE_OWNER', null, $this->getReference(OwnerFixtures::OB_OWNER_REFERENCE)];
        yield ['mohamed.sellami@telecom-sudparis.eu', 'Mohamed', 'Sellami', 'mohamed', 'ROLE_CLIENT', $this->getReference(ClientFixtures::MS_CLIENT_REFERENCE), null];
    }

    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
            OwnerFixtures::class,
        );
    }
}
