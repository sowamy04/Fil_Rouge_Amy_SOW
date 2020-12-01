<?php

// src/DataPersister

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Tags;

/**
 *
 */
class TagsDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Tags;
    }

    /**
     * @param Tags $data
     */
    public function persist($data, array $context = [])
    {
       return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setStatut(false);
        $this->entityManager->persist($data);
        $groupeTags = $data->getGroupeTags();
        foreach($groupeTags as $grpeTag) {
            $grpeTag->removeTag($data);
        }
        $this->entityManager->flush();
    }
}