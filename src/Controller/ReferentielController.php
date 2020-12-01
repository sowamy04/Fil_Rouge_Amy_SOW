<?php

namespace App\Controller;

use App\Entity\Referentiel;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     * "api/admin/referentiels", 
     * name="ajouter_referentiel", 
     * methods={"POST"},
     * defaults={
     *      "_controller"="\app\ControllerReferentielController::ajout_referentiel",
     *      "_api_resource_class"=Referentiel::class,
     *      "_api_collection_operation_name"="add_referentiel"
     *  }
     * )
     */
    public function ajout_referentiel(Request $request, GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        dd("ok");
        $em = $this->getDoctrine()->getManager();
        $referentielJson = $request->getContent();
        $referentielInfo = json_decode($referentielJson,true);
        $referentiel=new Referentiel();
        $referentiel->setLibelle($referentielInfo['libelle']);
        if(count($referentielInfo['groupecompetence']) == 0) {
            return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
        }
        else{
            $groupecompetences = $groupeCompetenceRepository->findAll();
             foreach ($referentielInfo['groupecompetence'] as $grcompetence) {
                foreach ($groupecompetences as $grpeComp) {
                    if ($grpeComp->getId() == $grcompetence) {
                        $referentiel->addGroupeCompetence($grpeComp);
                    }
                }
            }
            $em->persist($referentiel);
            $em->flush();
        }
        return new JsonResponse("Référiel et groupe de compétence ajouté avec succès",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route(
     * "api/admin/referentiels/{id}", 
     * name="modifier_referentiel", 
     * methods={"PUT"},
     * defaults={
     *      "_controller"="\app\ControllerReferentielController::modifier_groupe_competence",
     *      "_api_resource_class"=Referentiel::class,
     *      "_api_collection_operation_name"="update_referentiel"
     *  }
     * )
     */
    public function modifier_groupe_competence(Request $request, GroupeCompetenceRepository $groupecompetenceRepository, Referentiel $referentiel)
    {
        $em = $this->getDoctrine()->getManager();
        $referentielJson = $request->getContent();
        $referentielInfo = json_decode($referentielJson,true);
        $referentiel->setLibelle($referentielInfo['libelle']);
        if(count($referentielInfo['groupecompetence']) == 0) {
            return $this->json("Veuillez ajouter au moins un groupe de compétence svp!");
        }
        else{
            $groupecompetences = $groupecompetenceRepository->findAll();
            if ($referentielInfo['action'] == "ajouter") {
                foreach ($referentielInfo['groupecompetence'] as $grcompetence) {
                    foreach ($groupecompetences as $grpecomp) {
                        if ($grpecomp->getId() == $grcompetence) {
                            $referentiel->addGroupeCompetence($grpecomp);
                        }
                    }
                }
            }
            else if ($referentielInfo['action'] == "supprimer") {
                foreach ($referentielInfo['groupecompetence'] as $grcompetence) {
                    foreach ($groupecompetences as $grpecomp) {
                        if ($grpecomp->getId() == $grcompetence) {
                            $referentiel->removeGroupeCompetence($grpecomp);
                        }
                    }
                }
            }
            //$em->persist($referentiel);
            $em->flush();
            return new JsonResponse("Référentiel et groupe de compétence modifié avec succès",Response::HTTP_CREATED,[],true);
        }
    }

}
