<?php

namespace App\Controller;

use App\Entity\GroupeTags;
use App\Repository\TagsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeTagsController extends AbstractController
{
    /**
     * @Route(
     * "api/admin/grpetags", 
     * name="ajout_groupe_tags", 
     * methods={"POST"},
     * defaults={
     *      "_controller"="\app\ControllerGroupeTagsceController::ajout_groupe_tags",
     *      "_api_resource_class"=GroupeTags::class,
     *      "_api_collection_operation_name"="add_groupe_tags"
     *  }
     * )
     */
    public function ajout_groupe_tags(Request $request, TagsRepository $tagsRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $grpeTagsJson = $request->getContent();
        $grpeTagsInfo = json_decode($grpeTagsJson,true);
       $gpreTags=new GroupeTags();
       $gpreTags->setLibelle($grpeTagsInfo['libelle']);
        if(count($grpeTagsInfo['tag']) == 0) {
            return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
        }
        else{
            $tags = $tagsRepository->findAll();
             foreach ($grpeTagsInfo['tag'] as $tg) {
                foreach ($tags as $key => $tag) {
                    if ($tag->getId() == $tg) {
                        $gpreTags->addTag($tag);
                    }
                }
            }
            $em->persist($gpreTags);
            $em->flush();
        }
        return new JsonResponse("Groupe de tags ajouté avec succès",Response::HTTP_CREATED,[],true);
    }

    /**
     * @Route(
     * "api/admin/grpetags/{id}", 
     * name="modifier_groupe_tags", 
     * methods={"PUT"},
     * defaults={
     *      "_controller"="\app\ControllerGroupeTagsceController::modifier_groupe_competence",
     *      "_api_resource_class"=GroupeTags::class,
     *      "_api_collection_operation_name"="update_groupe_tags"
     *  }
     * )
     */
    public function modifier_groupe_competence(Request $request, TagsRepository $tagsRepository, GroupeTags $grpeTags)
    {
        $em = $this->getDoctrine()->getManager();
        $grpeTagsJson = $request->getContent();
        $grpeTagsInfo = json_decode($grpeTagsJson,true);
        $grpeTags->setLibelle($grpeTagsInfo['libelle']);
        if(count($grpeTagsInfo['tag']) == 0) {
            return $this->json("Veuillez ajouter au moins un niveau de compétence svp!");
        }
        else{
            $tags = $tagsRepository->findAll();
            if ($grpeTagsInfo['action'] == "ajouter") {
                foreach ($grpeTagsInfo['tag'] as $tg) {
                    foreach ($tags as $tag) {
                        if ($tag->getId() == $tg) {
                            $grpeTags->addTag($tag);
                        }
                    }
                }
            }
            else if ($grpeTagsInfo['action'] == "supprimer") {
                foreach ($grpeTagsInfo['tag'] as $tg) {
                    foreach ($tags as $tag) {
                        if ($tag->getId() == $tg) {
                            $grpeTags->removeTag($tag);
                        }
                    }
                }
            }
            $em->persist($grpeTags);
            $em->flush();
            return new JsonResponse("Groupe de tags modifié avec succès",Response::HTTP_CREATED,[],true);
        }
    }
}
