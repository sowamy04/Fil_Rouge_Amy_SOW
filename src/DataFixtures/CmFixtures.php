<?php

namespace App\DataFixtures;

use App\Entity\Cm;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CmFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserPasswordEncoderInterface $encode)
        {
            $this->encoder = $encode;
        }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i <2 ; $i++) { 
            $cm = new Cm();
            $password = $this->encoder->encodePassword($cm, 'pass1234');
            $cm->setPrenom($faker->firstName())
                  ->setNom($faker->lastName)
                  ->setTelephone($faker->phoneNumber)
                  ->setEmail("cm".$i."@gmail.com")
                  ->setPassword($password)
                  ->setPassword($password)
                  ->setProfils($this->getReference(ProfilFixtures::CM_REFERENCE))
            ;
            $manager->persist($cm);
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
