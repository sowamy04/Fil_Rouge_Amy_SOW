<?php

namespace App\DataFixtures;

use App\Entity\Formateur;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encode)
        {
            $this->encoder = $encode;
        }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i <2 ; $i++) { 
            $formateur = new Formateur();
            $password = $this->encoder->encodePassword($formateur, 'pass1234');
            $formateur  ->setSpecialite($faker->randomElement(['developpeur web', 'developpeur mobile']))
                        ->setPrenom($faker->firstName())
                        ->setNom($faker->lastName)
                        ->setTelephone($faker->phoneNumber)
                        ->setEmail("formateur".$i."@gmail.com")
                        ->setPassword($password)
                        ->setPassword($password)
                        ->setProfils($this->getReference(ProfilFixtures::FORMATEUR_REFERENCE))
                        ->setStatut(1)
                        ->setPhoto($faker->imageUrl(200,200))
            ;
            $manager->persist($formateur);
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
