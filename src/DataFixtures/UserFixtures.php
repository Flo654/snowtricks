<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    protected $encode;

    public function __construct(UserPasswordHasherInterface $encode)
    {
        $this->encode = $encode;
    }
        
    
    /**
     * Undocumented function
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {       
        $admin = new User;
        $admin->setEmail('flo654@hotmail.com')
            ->setFullName('Paul Person')
            ->setRoles(["ROLE_USER"])
            ->setPassword($this->encode->hashPassword($admin, 'password'))
            ->setCreatedAt(new DateTime('NOW'))
            ->setUpdatedAt(new DateTime('NOW'))
        ;
        $manager->persist($admin);



        $manager->flush();
    }
}
