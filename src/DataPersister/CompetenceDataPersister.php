<?php

// src/DataPersister

namespace App\DataPersister;

use App\Entity\Competence;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

/**
 *
 */
class CompetenceDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Competence;
    }

    /**
     * @param Competence $data
     */
    public function persist($data, array $context = [])
    {
       return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setStatut(false);
        $this->entityManager->persist($data);
        $niveaux = $data->getNiveaux();
        foreach($niveaux as $niveau) {
            $niveau->setStatut(false);
        }
        $groupeCompetence = $data->getGroupeCompetences();
        foreach($groupeCompetence as $grpeCompetence) {
            $grpeCompetence->removeCompetence($data);
        }
        $this->entityManager->flush();
    }
}