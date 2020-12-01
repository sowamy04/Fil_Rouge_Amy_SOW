<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{
    /**
     * @Route("api/admin/grpecompetences", 
     * name="ajout_groupe_competence", 
     * methods={"POST"},
     * defaults={
     *      "_controller"="\app\ControllerGroupeCompetenceController::ajout_groupe_competence",
     *      "_api_resource_class"=GroupeCompetence::class,
     *      "_api_collection_operation_name"="add_groupe_competence"
     *  }
     * )
     */
    public function ajout_groupe_competence(Request $request, CompetenceRepository $competenceRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $grpeCompetenceJson = $request->getContent();
        $grpeCompetenceInfo = json_decode($grpeCompetenceJson,true);
        $gprecompetence=new GroupeCompetence();
        $gprecompetence->setLibelle($grpeCompetenceInfo['libelle']);
        if(count($grpeCompetenceInfo['competence']) == 0) {
            return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
        }
        else{
            $competences = $competenceRepository->findAll();
             foreach ($grpeCompetenceInfo['competence'] as $competence) {
                foreach ($competences as $comp) {
                    if ($comp->getId() == $competence) {
                        $gprecompetence->addCompetence($comp);
                    }
                }
            }
            $em->persist($gprecompetence);
            $em->flush();
        }
        return new JsonResponse("Groupe de compétence ajouté avec succès",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route("api/admin/grpecompetences/{id}", 
     * name="update_groupe_competence", 
     * methods={"PUT"},
     * defaults={
     *      "_controller"="\app\ControllerGroupeCompetenceController::modifier_groupe_competence",
     *      "_api_resource_class"=GroupeCompetence::class,
     *      "_api_collection_operation_name"="update_groupe_competence"
     *  }
     * )
     */
    public function modifier_groupe_competence(Request $request, CompetenceRepository $competenceRepository, GroupeCompetence $gprecompetence)
    {
        $em = $this->getDoctrine()->getManager();
        $grpeCompetenceJson = $request->getContent();
        $grpeCompetenceInfo = json_decode($grpeCompetenceJson,true);
        $gprecompetence->setLibelle($grpeCompetenceInfo['libelle']);
        if(count($grpeCompetenceInfo['competence']) == 0) {
            return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
        }
        else{
            $competences = $competenceRepository->findAll();
            if ($grpeCompetenceInfo['action'] == "ajouter") {
                foreach ($grpeCompetenceInfo['competence'] as $competence) {
                    foreach ($competences as $comp) {
                        if ($comp->getId() == $competence) {
                            $gprecompetence->addCompetence($comp);
                        }
                    }
                }
            }
            else if ($grpeCompetenceInfo['action'] == "supprimer") {
                foreach ($grpeCompetenceInfo['competence'] as $competence) {
                    foreach ($competences as $comp) {
                        if ($comp->getId() == $competence) {
                            $gprecompetence->removeCompetence($comp);
                        }
                    }
                }
            }
            $em->persist($gprecompetence);
            $em->flush();
            return new JsonResponse("Groupe de compétence modifié avec succès",Response::HTTP_CREATED,[],true);
        }
    }
}
