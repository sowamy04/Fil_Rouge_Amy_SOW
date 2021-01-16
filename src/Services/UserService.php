<?php
namespace App\Services;

use App\Entity\Profil;
use App\Repository\AdminRepository;
use App\Repository\ApprenantRepository;
use App\Repository\CmRepository;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService 
{
    private $encode;
    private $manage;
    private $dn;
    private $formateurRepo;
    private $apprenantRepo;
    private $cmRepo;
    private $adminRepo;
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager,
    SerializerInterface $denormalizer, FormateurRepository $formateurRepository,
                                ApprenantRepository $apprenantRepository, CmRepository $cmRepository, AdminRepository $adminRepository)
    {
        $this->encode = $encoder;
        $this->manage = $manager;
        $this->dn = $denormalizer;
        $this->formateurRepo = $formateurRepository;
        $this->apprenantRepo = $apprenantRepository;
        $this->cmRepo = $cmRepository;
        $this->adminRepo = $adminRepository;
    }
    
    /* public function ajouter_user(Request $request, String $pf)
    {
        $requete = $request->request->all();
        $photo= $request->files->get('photo');
        if ($photo) {
            $photo= fopen($photo->getRealPath(),"rb");
        }
     
        $class="";
       
        $profilTab = $this->manage->getRepository(Profil::class)->findAll();
        foreach ($profilTab as $profil) {
            dd($profil);
            if ($profil->getLibelle() == $pf) {
                if ($pf == "APPRENANT") {
                    $class="App\Entity\Apprenant";
                    $password = "pass1234";
                    $requete = $this->dn->denorma$requete = $this->dn->denormalize($requete, $class);
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);lize($requete,$class);
                    $requete->setGenre("x")
                            ->setAdresse("xxxx")
                            ->setFirstConnexion(false)
                            ->setPrenom("Apprenant")
                            ->setNom("Apprenant")
                            ->setTelephone("xx xxx xx xx")
                            ->setPassword($this->encode->encodePassword($requete, $password))
                            ->setProfils($profil);
                    ;
                }

                else if ($pf == "FORMATEUR") {
                    $class="App\Entity\Formateur";
                    $requete = $this->dn->denormalize($requete, $class);
                    $requete->setSpecialite("Développeur web et/ou mobile et/ou CMS");
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);
                }

                else if ($pf == "CM") {
                    $class="App\Entity\Cm";
                    $requete = $this->dn->denormalize($requete, $class);
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);
                }

                else if ($pf == "ADMIN") {
                    $class="App\Entity\Admin";
                    $requete = $this->dn->denormalize($requete, $class, null);
                    $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
                    $requete->setProfils($profil);
                }
                
               else{
                   new JsonResponse("Profil inexistant");
               } 
                
            }
        }
           
           $requete->setStatut(true);
           $requete->setPhoto($photo);
           $requete->setPassword($this->encode->encodePassword($requete, $requete->getPassword()));
           $this->manage->persist($requete);
           $this->manage->flush();  

           return true;
        
    }

    public function modification_user(Request $request, String $class, int $id){
        $requete = $request->request->all();
        $prenom = $requete['prenom'];
        $nom = $requete['nom'];
        $email = $requete['email'];
        $password = $requete['password'];
        $telephone = $requete['telephone'];
        $photo= $request->files->get('photo');
        $pf = $requete["profil"];
        $photo= fopen($photo->getRealPath(),"rb");
        if ($class == "App\Entity\Formateur") {
            $formateur = $this->formateurRepo->findOneBy(["id"=>$id]);
            $profilTab = $this->manage->getRepository(Profil::class)->findAll();
            foreach ($profilTab as $profil) {
                if ($profil->getId() == intval($pf)) {
                    $formateur->setProfils($profil);
                }
            }
            $formateur
                      ->setPrenom($prenom)
                      ->setNom($nom)
                      ->setTelephone($telephone)
                      ->setEmail($email)
                      ->setPassword($this->encode->encodePassword($formateur, $password))
                      ->setStatut(true)
                      ->setPhoto($photo)
            ;
        }
        elseif ($class == "App\Entity\Apprenant") {
            $genre = $requete['genre'];
            $adresse = $requete['adresse'];
            $apprenant = $this->apprenantRepo->findOneBy(["id"=>$id]);
            $profilTab = $this->manage->getRepository(Profil::class)->findAll(); 
            foreach ($profilTab as $profil) {
                if ($profil->getId() == intval($pf)) { 
                    $apprenant->setProfils($profil);
                }
            }
            $apprenant->setAdresse($adresse)
                      ->setGenre($genre)
                      ->setFirstConnexion(true)
                      ->setPrenom($prenom)
                      ->setNom($nom)
                      ->setTelephone($telephone)
                      ->setEmail($email)
                      ->setPassword($this->encode->encodePassword($apprenant, $password))
                      ->setStatut(true)
                      ->setPhoto($photo)
            ;
        }
        elseif ($class == "App\Entity\Admin") {
            $admin = $this->adminRepo->findOneBy(["id"=>$id]);
            $profilTab = $this->manage->getRepository(Profil::class)->findAll();
            foreach ($profilTab as $profil) {
                if ($profil->getId() == intval($pf)) {
                    $admin->setProfils($profil);
                }
            }
            $admin
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setTelephone($telephone)
                ->setEmail($email)
                ->setPassword($this->encode->encodePassword($admin, $password))
                ->setStatut(true)
                ->setPhoto($photo)
            ;
        }
        elseif ($class == "App\Entity\Cm") {
            $cm = $this->cmRepo->findOneBy(["id"=>$id]);
            $profilTab = $this->manage->getRepository(Profil::class)->findAll();
            foreach ($profilTab as $profil) {
                if ($profil->getId() == intval($pf)) {
                    $cm->setProfils($profil);
                }
            }
            $cm
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setTelephone($telephone)
                ->setEmail($email)
                ->setPassword($this->encode->encodePassword($cm, $password))
                ->setStatut(true)
                ->setPhoto($photo)
            ;
        }
        else {
            return false;
        }
        $this->manage->flush();
        return true;
    } */

    /* public function ajouter_user($request, String $pf){
        $requete = $request->request->all();
        $photo= $request->files->get('photo');
        if ($photo) {
            $photo= fopen($photo->getRealPath(),"rb");
        }
        $profil = $this->profilRepository->findOneByLibelle($pf);
        if ($profil->getLibelle() == $pf) {
           
            if ($pf == "APPRENANT"){
                $class="App\\Entity\\Apprenant";
                    $password = "pass1234";
                    $requete = $this->serializer->denormalize($requete,$class);
                    $requete->setGenre("x")
                            ->setAdresse("xxxx")
                            ->setFirstConnexion(false)
                            ->setPrenom("Apprenant")
                            ->setNom("Apprenant")
                            ->setTelephone("xx xxx xx xx")
                            ->setPassword($this->encoder->encodePassword($requete, $password))
                            ->setProfils($profil);
                    ;
                }  else if ($pf == "FORMATEUR") {
                        $class="App\\Entity\\Formateur";
                        $requete = $this->serializer->denormalize($requete, $class);
                        $requete->setSpecialite("Développeur web et/ou mobile et/ou CMS");
                        $requete->setPassword($this->encoder->encodePassword($requete, $requete->getPassword()));
                        $requete->setProfils($profil);
                    }else if ($pf == "CM") {
                        $class="App\Entity\Cm";
                        $requete = $this->serializer->denormalize($requete, $class);
                        $requete->setPassword($this->encoder->encodePassword($requete, $requete->getPassword()));
                        $requete->setProfils($profil);
                    }
        
                    else if ($pf == "ADMIN") {
                        $class="App\\Entity\\Admin";
                        $requete = $this->serializer->denormalize($requete, $class, null);
                        $requete->setPassword($this->encoder->encodePassword($requete, $requete->getPassword()));
                        $requete->setProfils($profil);
                    }
                    $requete->setStatut(true);
                    if($photo){
                        $requete->setPhoto($photo);
                    }
                    $this->manager->persist($requete);
                    $this->manager->flush(); 
                    return true;
            }
    }

    public function modification_user($request, $class, $id){
        $requete = $request->request->all();
        $prenom = $requete['prenom'];
        $nom = $requete['nom'];
        $email = $requete['email'];
        $password = $requete['password'];
        $telephone = $requete['telephone'];
        $photo= $request->files->get('photo');
        $pf = $requete["profil"];
        $photo= fopen($photo->getRealPath(),"rb");
        $user = $this->userRepository->findOneById($id);
        if ($class == "App\Entity\Formateur") {
            $specialite = $requete['specialite'];
            $user->setSpecialite($specialite)
                      ->setPrenom($prenom)
                      ->setNom($nom)
                      ->setTelephone($telephone)
                      ->setEmail($email)
                      ->setPassword($this->encode->encodePassword($user, $password))
                      ->setStatut(true)
                      ->setPhoto($photo)
            ;
        }
        else if ($class == "App\Entity\Apprenant") {
            $genre = $requete['genre'];
            $adresse = $requete['adresse'];
            $user->setAdresse($adresse)
                      ->setGenre($genre)
                      ->setPrenom($prenom)
                      ->setNom($nom)
                      ->setTelephone($telephone)
                      ->setEmail($email)
                      ->setPassword($this->encode->encodePassword($user, $password))
                      ->setStatut(true)
                      ->setPhoto($photo)
            ;
        }
        elseif ($class == "App\Entity\Admin") {
            $user
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setTelephone($telephone)
                ->setEmail($email)
                ->setPassword($this->encode->encodePassword($user, $password))
                ->setStatut(true)
                ->setPhoto($photo)
            ;
        }
        elseif ($class == "App\Entity\Cm") {
            $user
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setTelephone($telephone)
                ->setEmail($email)
                ->setPassword($this->encode->encodePassword($user, $password))
                ->setStatut(true)
                ->setPhoto($photo)
            ;
        }
        else {
            return false;
        }
        $this->manager->flush();
        return true;
    } */
}