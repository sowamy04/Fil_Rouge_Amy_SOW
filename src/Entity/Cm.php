<?php

namespace App\Entity;

use App\Repository\CmRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CmRepository::class)
 */
class Cm extends User
{
    
}
