<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Service\UserService ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route(
     * path="/api/apprenants",
     *  name="add_apprenant",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\ControllerApprenantController::ajouter_apprenant",
     *      "_api_resource_class"=Apprenant::class,
     *      "_api_collection_operation_name"="ajouter_apprenant",
     *  }
     * )
     */
    public function ajouter_apprenant( Request $request)
    {
        
        if($this->userService->ajout_utilisateur($request)){
            return $this->json("Apprenant ajouté avec succès!", 201);
        }
        return $this->json("Erreur lors de l'insertion!",400); 
    
        
    }
    
    /**
     * @Route(
     * path="/api/apprenants/{id}",
     *  name="update_apprenant",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\ControllerApprenantController::modifier_apprenant",
     *      "_api_resource_class"=Apprenant::class,
     *      "_api_collection_operation_name"="modifier_apprenant",
     *  }
     * )
     */
    public function modifier_apprenant(int $id, Request $request)
    {
        $class = "App\\Entity\\Apprenant";
        if($this->userService->modification_user($request,$class,(int)$id)){
            return $this->json('Vous avez modifé ces informations avec succès');
        }
        return $this->json("Erreur lors de la modification!");
    }
    
}
