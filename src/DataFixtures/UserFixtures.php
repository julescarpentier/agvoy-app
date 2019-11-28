<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const MR_USER_REFERENCE = 'mr-user';
    public const JC_USER_REFERENCE = 'jc-user';
    public const OB_USER_REFERENCE = 'ob-user';
    public const MS_USER_REFERENCE = 'ms-user';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email, $firstname, $familyname, $plainPassword, $roles, $client, $owner, $reference]) {
            $user = new User();
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setFirstname($firstname);
            $user->setFamilyname($familyname);
            $user->setPassword($encodedPassword);
            $user->setRoles($roles);
            $user->setClient($client);
            $user->setOwner($owner);
            $manager->persist($user);

            $this->addReference($reference, $user);
        }

        $manager->flush();
    }

    private function getUserData()
    {
        yield [
            'marie.reinbigler@telecom-sudparis.eu',
            'Marie',
            'Reinbigler',
            'marie',
            ['ROLE_ADMIN'],
            $this->getReference(ClientFixtures::MR_CLIENT_REFERENCE),
            $this->getReference(OwnerFixtures::MR_OWNER_REFERENCE),
            self::MR_USER_REFERENCE,
        ];

        yield [
            'jules.carpentier@telecom-sudparis.eu',
            'Jules',
            'Carpentier',
            'jules',
            ['ROLE_ADMIN'],
            $this->getReference(ClientFixtures::JC_CLIENT_REFERENCE),
            $this->getReference(OwnerFixtures::JC_OWNER_REFERENCE),
            self::JC_USER_REFERENCE,
        ];

        yield [
            'olivier.berger@telecom-sudparis.eu',
            'Olivier',
            'Berger',
            'olivier',
            ['ROLE_CLIENT', 'ROLE_OWNER'],
            $this->getReference(ClientFixtures::OB_CLIENT_REFERENCE),
            $this->getReference(OwnerFixtures::OB_OWNER_REFERENCE),
            self::OB_USER_REFERENCE,
        ];

        yield [
            'mohamed.sellami@telecom-sudparis.eu',
            'Mohamed',
            'Sellami',
            'mohamed',
            ['ROLE_CLIENT'],
            $this->getReference(ClientFixtures::MS_CLIENT_REFERENCE),
            null,
            self::MS_USER_REFERENCE,
        ];
    }

    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
            OwnerFixtures::class,
        );
    }
}
