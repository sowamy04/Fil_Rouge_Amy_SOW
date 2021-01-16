<?php

namespace App\Controller;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

   /**
     * @Route(
     *  "/api/admin/users",
     *  name="add_user",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\ControllerUserController::ajouter_user",
     *      "_api_resource_class"=User::class,
     *      "_api_collection_operation_name"="add_user"
     *  }
     * )
     */
    public function ajouter_user( Request $request)
    {
        
       if($this->userService->ajout_utilisateur($request)){
            return $this->json('Utilisateur ajouté avec succès',201);
        }
        return $this->json("Erreur lors de l'insertion!",400);
    
       
    }
    
    /**
     * @Route(
     * "/api/admin/users/{id}",
     *  name="update_user",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\ControllerUserController::modifier_user",
     *      "_api_resource_class"=User::class,
     *      "_api_collection_operation_name"="modifier_user"
     *  }
     * )
     */
    public function modifier_user(int $id, Request $request)
    {
        $class = "App\\Entity\\User";
        if($this->userService->modification_user($request,$class,(int)$id)){
            return $this->json('Vous avez modifé vos informations avec succès',200);
        }
        return $this->json("Erreur lors de la modification!",400);
    }
}
