<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *  attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}},
 *      "deserialize"=false,
 *        "swagger_context"={
 *           "consumes"={
 *              "multipart/form-data",
 *             },
 *             "parameters"={
 *                "in"="formData",
 *                "name"="file",
 *                "type"="file",
 *                "description"="The file to upload",
 *              },
*           },
 *     },
 *  collectionOperations={
 *      "lister_formateurs":{
 *          "method" : "GET",
 *          "path":"formateurs",
 *          "normalization_context"={"groups":"formateur:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *  "ajouter_formateur":{
 *          "method" : "POST",
 *          "path":"formateurs",
 *          "normalization_context"={"groups":"formateur:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name" = "add_formateur"
 *      },
 *  },
 *  itemOperations={
 *      "modifier_formateur":{
 *          "method" : "PUT",
 *          "path":"formateurs/{id}",
 *          "normalization_context"={"groups":"formateur:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name":"update_formateur"
 *      },
 * "get"
 *  }
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"formateur:read"})
     */
    private $specialite;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateurs")
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

        return $this;
    }
}
