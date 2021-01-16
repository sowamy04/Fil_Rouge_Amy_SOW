<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Repository\CompetenceRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ReferentielController extends AbstractController
{
    private $dn;
    public function __construct(DenormalizerInterface $denormalizerInterface)
    {
        $this->dn = $denormalizerInterface;
    } 

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
        $requete = $request->request->all();
        $file= $request->files->get('programme');
        $file= fopen($file->getRealPath(),"rb");
        $em = $this->getDoctrine()->getManager();
        $referentiel = $this->dn->denormalize($requete, Referentiel::class, true);
        $referentiel->setProgramme($file);
        if(count($requete['groupeCompetence']) == 0) {
            return $this->json("Veuillez ajouter au moins un groupe de compétence svp!");
        }
        else{
            $groupecompetences = $groupeCompetenceRepository->findAll();
            foreach ($requete['groupeCompetence'] as $grcompetence) {
                foreach ($groupecompetences as $grpeComp) {
                    if ($grpeComp->getId() == intval($grcompetence)) {
                        $referentiel->addGroupeCompetence($grpeComp);
                    }
                }
            }
            $em->persist($referentiel);
            $em->flush();
        }
        return new JsonResponse("Référentiel et groupe de compétence ajouté avec succès",Response::HTTP_CREATED,[],true);
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
        $requete = $request->request->all();
        $file= $request->files->get('programme');
        $file= fopen($file->getRealPath(),"rb");
        $referentiel = $this->dn->denormalize($requete, Referentiel::class, true);
        $referentiel->setProgramme($file);
        if(count($requete['groupeCompetence']) == 0) {
            return $this->json("Veuillez ajouter au moins un groupe de compétence svp!");
        }
        else{
            $groupecompetences = $groupecompetenceRepository->findAll();
            if ($requete['action'] == "ajouter") {
                foreach ($requete['groupeCompetence'] as $grcompetence) {
                    foreach ($groupecompetences as $grpecomp) {
                        if ($grpecomp->getId() == intval($grcompetence)) {
                            $referentiel->addGroupeCompetence($grpecomp);
                        }
                    }
                }
            }
            else if ($requete['action'] == "supprimer") {
                foreach ($requete['groupeCompetence'] as $grcompetence) {
                    //dd((int)$grcompetence);
                    foreach ($groupecompetences as $grpecomp) {
                        //dd($groupecompetences);
                        if ($grpecomp->getId() == (int)($grcompetence)) {
                            $referentiel->removeGroupeCompetence($grpecomp);
                        }
                    }
                }
            }
            
            $em->persist($referentiel);
            $em->flush();
            return new JsonResponse("Référentiel modifié avec succès en ajoutant/supprimant des groupes de compétences",Response::HTTP_CREATED,[],true);
        }
    }

    /**
     * @Route(
     * "api/admin/referentiels/{id}/grpecompetences/{id2}", 
     * name="lister_competences", 
     * methods={"GET"},
     * defaults={
     *      "_controller"="\app\ControllerReferentielController::lister_competence_groupecompetence_repository",
     *      "_api_resource_class"=Referentiel::class,
     *      "_api_collection_operation_name"="afficher_groupecompetence_referentiel"
     *  }
     * )
     */
    public function lister_competence_groupecompetence_repository( int $id, $id2, CompetenceRepository $comp)
    {
        $competences = $comp->findGroupeCompetenceByReferentiel($id, $id2);
        return $this->json($competences, 200);
    }
}