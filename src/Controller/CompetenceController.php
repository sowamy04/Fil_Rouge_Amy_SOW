<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Competence;
use App\Repository\NiveauRepository;
use App\Repository\CompetenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CompetenceController extends AbstractController
{
    private $dn;
    public function __construct(DenormalizerInterface $denormalizer)
    {
        $this->dn = $denormalizer;
    }

    /**
     * @Route(
     * "/api/admin/competences", 
     * name="add_competence", 
     * methods={"POST"},
     * defaults={
     *      "_controller"="\app\ControllerCompetenceController::ajout_competence",
     *      "_api_resource_class"=Competence::class,
     *      "_api_collection_operation_name"="add_competence"
     *  }
     * )
     */
    public function ajout_competence(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $competenceJson = $request->getContent();
        $competenceInfo = json_decode($competenceJson,true);
       $competence=new Competence();
       $competence->setLibelle($competenceInfo['libelle']);
        if(count($competenceInfo['niveau']) == 0) {
            return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
        }
        else if ((count($competenceInfo['niveau']) <3) or (count($competenceInfo['niveau']) >3)) {
            return $this->json("Vous devez ajouter les trois niveaux de compétences");
        }
        else{
             foreach ($competenceInfo['niveau'] as $niveau) {
                $niveauxObj = $this->dn->denormalize($niveau, Niveau::class, true);
                $em->persist($niveauxObj);
                $competence->addNiveau($niveauxObj);
            }
            $em->persist($competence);
            $em->flush();
        }
        return new JsonResponse("Compétence ajoutée avec succès",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route(
     * "/api/admin/competences/{id}", 
     * name="update_competence", 
     * methods={"PUT"},
     * defaults={
     *      "_controller"="\app\ControllerCompetenceController::modifier_competence",
     *      "_api_resource_class"=Competence::class,
     *      "_api_collection_operation_name"="update_competence_id"
     *  }
     * )
     */
    public function modifier_competence(int $id, Request $request, CompetenceRepository $competenceRepository, NiveauRepository $niveauRepository, Competence $competence)
    {
        $competenceInfoRecu = $competenceRepository->findOneBy(["id"=>$id]);
        if (!empty($competenceInfoRecu)){
            $em = $this->getDoctrine()->getManager();
            $competenceJson = $request->getContent();
            $competenceInfo = json_decode($competenceJson,true);
            $competence->setLibelle($competenceInfo['libelle']);

            if(count($competenceInfo['niveau']) == 0) {
                return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
            }
            else if ((count($competenceInfo['niveau']) <3) or (count($competenceInfo['niveau']) >3)) {
                return $this->json("Vous devez ajouter les trois niveaux de compétences");
            }
            else{
                $niveaux = $niveauRepository->findAll();
                foreach ($niveaux as  $nivObj) { 
                    if ($nivObj->getCompetence()->getId() == $id) {
                        $tabNiveau[] = $nivObj;
                    }
                }
                foreach ($tabNiveau as $key => $unNiveau) {
                    $unNiveau->setLibelle($competenceInfo['niveau'][$key]['libelle'])
                                ->setCritereEvaluation($competenceInfo['niveau'][$key]['critereEvaluation'])
                                ->setGroupeAction($competenceInfo['niveau'][$key]['groupeAction'])
                    ;
                }
                $em->flush();
            }

            return new JsonResponse("Compétence modifiée avec succès",Response::HTTP_CREATED,[],true);
        }
        
    }
}
