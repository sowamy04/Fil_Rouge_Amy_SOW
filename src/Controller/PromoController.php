<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoController extends AbstractController
{
    /**
     * @Route("api/admin/promo", 
     * name="add_promo", 
     * methods={"POST"},
     * defaults={
     *      "_controller"="\app\ControllerPromoController::ajout_promo",
     *      "_api_resource_class"=Promo::class,
     *      "_api_collection_operation_name"="ajouter_promo"
     *  }
     * )
     */
    public function ajout_promo(Request $request, ReferentielRepository $referencielRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $promoJson = $request->getContent();
        $promoInfo = json_decode($promoJson,true);
        $promo=new Promo();
        $promo->setLibelle($promoInfo['libelle']);
        $promo->setDescription($promoInfo['description'])
              ->setLangue($promoInfo['langue'])
              ->setLieu($promoInfo['lieu'])
              ->setReferentAgate($promoInfo['referentAgate'])
              ->setFabrique($promoInfo['fabrique'])
              ->setDateDebut(new \DateTime($promoInfo['dateDebut']))
              ->setDateFin(new \DateTime($promoInfo['dateFin']))
        ;

        if(count($promoInfo['ref']) == 0) {
            return $this->json("Veuillez ajouter au moins un référentiel svp!");
        }
        else{
            $referentiels = $referencielRepository->findAll();
             foreach ($promoInfo['ref'] as $referent) {
                foreach ($referentiels as $ref) {
                    if ($ref->getId() == $referent) {
                        $promo->addReferentiel($ref);
                    }
                }
            }
            $em->persist($promo);
            $em->flush();
        }
        return new JsonResponse("Promo ajouté avec succès",Response::HTTP_CREATED,[],true);
    }
}
