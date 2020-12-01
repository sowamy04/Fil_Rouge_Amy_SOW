<?php

namespace App\Controller;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    /**
     * @Route(
     * "api/admins/users", 
     * name="add_admin"
     * )
     */
    public function ajouter_admin( AdminRepository $adminRepository)
    {
        $admins = $adminRepository->findAll();
        foreach ($admins as $key => $admin) {
            if ($admin->getProfils()->getLibelle() == "ADMIN") {
                $allAdmins[] = $admin;
            }
        }

        return $this->json("");
    }
}
