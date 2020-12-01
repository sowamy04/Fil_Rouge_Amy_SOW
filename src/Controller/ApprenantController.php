<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
{
    private $UserService;
    public function __construct(UserService $userService)
    {
        $this->UserService = $userService; 
    }

    /**
     * @Route(
     * "/api/apprenants",
     *  name="add_apprenant",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\ControllerApprenantController::ajouter_apprenant",
     *      "_api_resource_class"=Apprenant::class,
     *      "_api_collection_operation_name"="add_apprenant"
     *  }
     * )
     */
    public function ajouter_apprenant( Request $request)
    {
        $profil = "APPRENANT";
        if(  $this->UserService->ajouter_user($request, $profil)){
            return $this->json("Apprenant ajouté avec succès!", 200);
        }
        return $this->json("Erreur lors de l'insertion!");
    
        
    }

    /**
     * @Route(
     * "/api/apprenants/{id}",
     *  name="update_apprenant",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\ControllerApprenantController::modifier_apprenant",
     *      "_api_resource_class"=Apprenant::class,
     *      "_api_collection_operation_name"="modifier_apprenant"
     *  }
     * )
     */
    public function modifier_apprenant(int $id, Request $request, ApprenantRepository $apprenantRepository)
    {
        $class = "App\Entity\Apprenant";
        if ($this->UserService->modification_user($request, $class, $id)) {
            return $this->json('Vous avez modifé vos informations avec succès');
        }
        return $this->json("Erreur lors de la modification!");
    }
}
