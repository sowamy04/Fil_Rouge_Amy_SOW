<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Formateur;
use App\Repository\FormateurRepository;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    private $UserService;
    public function __construct(UserService $userService)
    {
        $this->UserService = $userService; 
    }
    
    /**
     * @Route(
     *  "/api/formateurs",
     *  name="add_formateur",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\ControllerFormateurController::ajouter_formateur",
     *      "_api_resource_class"=Formateur::class,
     *      "_api_collection_operation_name"="ajouter_formateur"
     *  }
     * )
     */
    public function ajouter_formateur(Request $request)
    {
        $profil = "FORMATEUR";
        if(  $this->UserService->ajouter_user($request, $profil)){
            return $this->json("Formateur ajouté avec succès!", 200);
        }
        return $this->json("Erreur lors de l'insertion!");
       
    }

    /**
     * @Route(
     * "/api/formateurs/{id}",
     *  name="update_formateur",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\ControllerFormateurController::modifier_formateur",
     *      "_api_resource_class"=Formateur::class,
     *      "_api_collection_operation_name"="modifier_formateur"
     *  }
     * )
     */
    public function modifier_formateur(int $id, Request $request, FormateurRepository $formateurRepository)
    {
        $class = "App\Entity\Formateur";
        if ($this->UserService->modification_user($request, $class, $id)) {
            return $this->json('Vous avez modifé vos informations avec succès');
        }
        return $this->json("Erreur lors de la modification!");
    
    }

}
