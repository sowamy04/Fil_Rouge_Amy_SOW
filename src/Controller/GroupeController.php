<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    /**
     * @Route(
     * "api/admin/groupes", 
     * name="ajouter_groupe", 
     * methods={"POST"},
     * defaults={
     *      "_controller"="\app\ControllerGroupeController::ajout_Groupe",
     *      "_api_resource_class"=Groupe::class,
     *      "_api_collection_operation_name"="add_groupe"
     *  }
     * )
     */
    public function ajout_Groupe(Request $request, ApprenantRepository $apprenantRepository, FormateurRepository $formateurRepository, PromoRepository $promoRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $GroupeJson = $request->getContent();
        $GroupeInfo = json_decode($GroupeJson,true);
        $groupe=new Groupe();
        $groupe->setLibelle($GroupeInfo['libelle']);
        if(count($GroupeInfo['apprenant']) == 0 or count($GroupeInfo['formateur']) == 0) {
            return $this->json("Veuillez ajouter au moins un apprenant et/ou un formateur svp!");
        }
        else{
            $apprenants = $apprenantRepository->findAll();
             foreach ($GroupeInfo['apprenant'] as $app) {
                foreach ($apprenants as $apprenant) {
                    if ($apprenant->getId() == $app) {
                        $groupe->addApprenant($apprenant);
                    }
                }
            }
            $formateurs = $formateurRepository->findAll();
            foreach ($GroupeInfo['formateur'] as $form) {
                foreach ($formateurs as $formateur) {
                    if ($formateur->getId() == $form) {
                        $groupe->addFormateur($formateur);
                    }
                }
            }
            $pro = $GroupeInfo['promo'];
            $promos = $promoRepository->findAll();
            foreach ($promos as $promo) {
                if ($promo->getId() == $pro) {
                    $groupe->setPromos($promo);
                }
            }
            $em->persist($groupe);
            $em->flush();
            return new JsonResponse("Groupe ajouté avec succès en lui affectant des apprennants et formateurs",Response::HTTP_CREATED,[],true);
        }
        
    }

    /**
     * @Route(
     * "api/admin/groupes/{id}", 
     * name="mofifier_groupe", 
     * methods={"PUT"},
     * defaults={
     *      "_controller"="\app\ControllerGroupeController::modifier_groupe",
     *      "_api_resource_class"=Groupe::class,
     *      "_api_collection_operation_name"="update_groupe"
     *  }
     * )
     */
    public function modifier_groupe(Request $request, ApprenantRepository $apprenantRepository, Groupe $groupe,PromoRepository $promoRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $GroupeJson = $request->getContent();
        $GroupeInfo = json_decode($GroupeJson,true);
        $groupe->setLibelle($GroupeInfo['libelle']);
        if(count($GroupeInfo['apprenant']) == 0) {
            return $this->json("Veuillez ajouter au moins un apprenant svp!");
        }
        else{
            $apprenants = $apprenantRepository->findAll();
            if ($GroupeInfo['action'] == "ajouter") {
                foreach ($GroupeInfo['apprenant'] as $app) {
                    foreach ($apprenants as $apprenant) {
                        if ($apprenant->getId() == $app) {
                            $groupe->addApprenant($apprenant);
                        }
                    }
                }
            }
            else if ($GroupeInfo['action'] == "supprimer") {
                foreach ($GroupeInfo['apprenant'] as $app) {
                    foreach ($apprenants as $apprenant) {
                        if ($apprenant->getId() == $app) {
                            $groupe->removeApprenant($apprenant);
                        }
                    }
                }
            }
            $pro = $GroupeInfo['promo'];
            $promos = $promoRepository->findAll();
            foreach ($promos as $promo) {
                if ($promo->getId() == $pro) {
                    $groupe->setPromos($promo);
                }
            }
            //$em->persist($referentiel);
            $em->flush();
            return new JsonResponse("Groupe modifié en ajoutant apprenant ou supprimant apprenant avec succès",Response::HTTP_CREATED,[],true);
        }
    }
}
