<?php


namespace App\SurchargeToken;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class TokenRewritten
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // On récupère l'utilisateur
        $user = $event->getUser();

        // On enrichit le data du Token
        $data = $event->getData();

        $data['statut'] = $user->getStatut();
        $data['prenom'] = $user->getPrenom();
        $data['photo'] = $user->getPhoto();
        if ($user->getProfils()->getLibelle() == "APPRENANT") {
            $data['firstConnexion'] = $user->getFirstConnexion();
        }

        $event->setData($data);
    }
}