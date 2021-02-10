<?php

namespace App\Service;

use App\Entity\Profil;
use App\Repository\ApprenantRepository;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService {
    private $dn;
    private $encode;
    private $userRepository;
    private $manage;
    private $profilRepository;
    private $apprenantRepository;
    public function __construct(UserPasswordEncoderInterface $encoder, DenormalizerInterface $denormalize, 
    UserRepository $userRepository, EntityManagerInterface $manager, ProfilRepository $profilRepository,
    ApprenantRepository $apprenantRepository)
    {
        $this->dn = $denormalize;
        $this->encode = $encoder;
        $this->userRepository = $userRepository;
        $this->manage = $manager;
        $this->profilRepository = $profilRepository;
        $this->apprenantRepository = $apprenantRepository;
    }

    public function ajout_utilisateur($request){
        $requete = $request->request->all();
        $pf = (int)$requete['profil'];
        $photo= $request->files->get('photo');
        if ($photo) {
            $photo= fopen($photo->getRealPath(),"rb");
        }
        $profilTab = $this->manage->getRepository(Profil::class)->findAll();
        $class="";
       
        foreach ($profilTab as $profil) {
            if ($profil->getId() == $pf) {
                if ($profil->getLibelle() == "ADMIN") {
                    $class="App\\Entity\\Admin";
                    $requete = $this->dn->denormalize($requete, $class, null);
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);
                }
                elseif ($profil->getLibelle() == "CM") {
                    $class="App\\Entity\\Cm";
                    $requete = $this->dn->denormalize($requete, $class, null);
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);
                }
                elseif ($profil->getLibelle() == "FORMATEUR") {
                    $class="App\\Entity\\Formateur";
                    $requete = $this->dn->denormalize($requete, $class, null);
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);
                }
                elseif ($profil->getLibelle() == "APPRENANT"){
                    $class="App\\Entity\\Apprenant";
                    $password = "pass1234";
                    $requete = $this->dn->denormalize($requete, $class);
                    $requete->setGenre("x")
                            ->setAdresse("xxxx")
                            ->setFirstConnexion(true)
                            ->setPrenom("Apprenant")
                            ->setNom("Apprenant")
                            ->setTelephone("xx xxx xx xx")
                            ->setPassword($this->encode->encodePassword($requete, $password))
                            ->setProfils($profil);
                    ;
                }
                $requete->setStatut(true);
                $requete->setPhoto($photo);
                $this->manage->persist($requete);
                $this->manage->flush();  
                return true;
            }

        }
    }

    public function modification_user($request, $class, $id)
    {
        $requete = $request->request->all();
        $prenom = $requete['prenom'];
        $nom = $requete['nom'];
        $email = $requete['email'];
        $password = $requete['password'];
        $telephone = $requete['telephone'];
        $photo= $request->files->get('photo');
        if ($photo) {
            $photo= fopen($photo->getRealPath(),"rb");
        }
        $pf = $requete["profil"];
        if ($class == "App\\Entity\\Apprenant"){
            $genre = $requete['genre'];
            $adresse = $requete['adresse'];
            $apprenant = $this->apprenantRepository->findOneBy(["id"=>$id]);
            $apprenant->setAdresse($adresse)
                      ->setGenre($genre)
                      ->setFirstConnexion(false)
                      ->setPrenom($prenom)
                      ->setNom($nom)
                      ->setTelephone($telephone)
                      ->setEmail($email)
                      ->setStatut(true)
                      ->setPhoto($photo)
            ;
            if ($password) {
                $apprenant->setPassword($this->encode->encodePassword($apprenant, $password));
             }
        }
        else{
            $user = $this->userRepository->findOneBy(["id"=>$id]);
            $user
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setTelephone($telephone)
                ->setEmail($email)
                ->setStatut(true)
                ->setPhoto($photo)
            ;
            if ($password) {
               $user->setPassword($this->encode->encodePassword($user, $password));
            }
        }
        $this->manage->flush();
        return true;
    }

    
}