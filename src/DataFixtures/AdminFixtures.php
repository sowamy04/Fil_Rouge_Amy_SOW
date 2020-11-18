<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Admin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encode)
        {
            $this->encoder = $encode;
        }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i <2 ; $i++) { 
            $admin = new Admin();
            $password = $this->encoder->encodePassword($admin, 'pass1234');
            $admin->setPrenom($faker->firstName())
                  ->setNom($faker->lastName)
                  ->setTelephone($faker->phoneNumber)
                  ->setEmail("admin".$i."@gmail.com")
                  ->setPassword($password)
                  ->setProfils($this->getReference(ProfilFixtures::ADMIN_REFERENCE))
            ;
            $manager->persist($admin);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
