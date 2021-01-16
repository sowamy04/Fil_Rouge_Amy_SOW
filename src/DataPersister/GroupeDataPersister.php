<?php

// src/DataPersister

namespace App\DataPersister;

use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

/**
 *
 */
class GroupeDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this-> entityManager = $entityManager;
    }


    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Groupe;
    }

    /**
     * @param Groupe $data
     */
    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setStatut(false);
        $this->entityManager->persist($data);
        $apprenants = $data->getApprenants();
        foreach($apprenants as $apprenant) {
            $data->removeApprenant($apprenant);
        }
        $this->entityManager->flush();
    }
}