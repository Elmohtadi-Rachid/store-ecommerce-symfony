<?php

namespace App\DataFixtures;

use app\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use symfony\Component\PasswordHasher\Hasher;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {
         $user = new User();

         $palainPassword = "admin123";
         $hashedPassword= $this->hasher->hashPassword( $user, $palainPassword);
         $user->setUsername('admin');
         $user->setPassword($hashedPassword);
         $user->setRoles(['RELE_ADMIN']);
         $manager->persist($user);

        $manager->flush();
    }
}
