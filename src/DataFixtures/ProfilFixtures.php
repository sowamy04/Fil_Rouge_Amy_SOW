<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = "ADMIN";
    public const CM_REFERENCE = "CM";
    public const FORMATEUR_REFERENCE = "FORMATEUR";
    public const APPRENANT_REFERENCE = "APPRENANT";

    public function load(ObjectManager $manager)
    {
        $profils = array("ADMIN", "CM", "FORMATEUR", "APPRENANT");
        foreach ($profils as $key => $value) {
            $profil = new Profil();
            $profil->setLibelle($value);
            if ($value == "ADMIN") {
                $this->addReference(self::ADMIN_REFERENCE, $profil);
            }
            elseif ($value == "CM") {
                $this->addReference(self::CM_REFERENCE, $profil);
            }
            elseif ($value == "FORMATEUR") {
                $this->addReference(self::FORMATEUR_REFERENCE, $profil);
            }
            else {
                $this->addReference(self::APPRENANT_REFERENCE, $profil);
            }

            
            $manager->persist($profil);
    
        }
            

        $manager->flush();
    }
}
