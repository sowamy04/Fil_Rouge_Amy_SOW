<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Apprenant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encode)
        {
            $this->encoder = $encode;
        }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i <2 ; $i++) { 
            $apprenant = new Apprenant();
            $password = $this->encoder->encodePassword($apprenant, 'pass1234');
            $apprenant  ->setGenre($faker->randomElement(['male', 'female']))
                        ->setAdresse($faker->address)
                        ->setPrenom($faker->firstName())
                        ->setNom($faker->lastName)
                        ->setTelephone($faker->phoneNumber)
                        ->setEmail("apprenant".$i."@gmail.com")
                        ->setPassword($password)
                        ->setProfils($this->getReference(ProfilFixtures::APPRENANT_REFERENCE))
            ;
            $manager->persist($apprenant);
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
