<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Services\UserService;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $UserService;
    public function __construct(UserService $userService)
    {
        $this->UserService = $userService; 
    }
    
    /**
     * @Route(
     *  "/api/admin/users",
     *  name="add_admin",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\ControllerAdminController::ajouter_admin",
     *      "_api_resource_class"=Admin::class,
     *      "_api_collection_operation_name"="add_admin"
     *  }
     * )
     */
    public function ajouter_admin(Request $request)
    {
        dd("ok");
        $profil = "ADMIN";
        if(  $this->UserService->ajouter_user($request, $profil)){
            return $this->json("Admin ajouté avec succès!", 200);
        }
        return $this->json("Erreur lors de l'insertion!");
    }

    /**
     * @Route(
     * "/api/admin/users/{id}",
     *  name="update_admin",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\ControllerAdminController::modifier_admin",
     *      "_api_resource_class"=Admin::class,
     *      "_api_collection_operation_name"="modifier_admin"
     *  }
     * )
     */
    public function modifier_admin(int $id, Request $request, AdminRepository $adminRepository)
    {
        $class = "App\Entity\Admin";
        if ($this->UserService->modification_user($request, $class, $id)) {
            return $this->json('Vous avez modifé vos informations avec succès');
        }
        return $this->json("Erreur lors de la modification!");
    }
}
