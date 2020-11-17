<?php

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class UserFixtures extends Fixture{

    public function load(ObjectManager $manager)
    {
        $profil = new Profil();
        $profil->setLibelle("test");
        $manager->persist($profil);
        $manager->flush();
    }
}