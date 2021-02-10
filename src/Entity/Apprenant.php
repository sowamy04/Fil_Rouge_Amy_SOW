<?php

namespace App\Entity;

use App\Entity\ProfilDeSortie;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
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
 *      "lister_apprenant":{
 *          "method" : "GET",
 *          "path":"apprenants",
 *          "normalization_context"={"groups":"apprenant:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *      "ajouter_apprenant":{
 *          "method" : "POST",
 *          "path":"/apprenants",
 *          "normalization_context"={"groups":"apprenant:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name" = "add_apprenant",
 *          
 *      },
 *  },
 *  itemOperations={
 *      "afficher_apprenant":{
 *          "method" : "GET",
 *          "path":"apprenants/{id}",
 *          "normalization_context"={"groups":"apprenant:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 *  "modifier_apprenant":{
 *          "method" : "PUT",
 *          "path":"apprenants/{id}",
 *          "normalization_context"={"groups":"apprenant:read"},
 *          "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          "route_name" = "update_apprenant",
 *      },
 *  }
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenant:read", "groupe:read"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apprenant:read", "groupe:read"})
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants", cascade={"persist"})
     * @Groups({"apprenant:read"})
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"apprenant:read"})
     */
    private $firstConnexion = false;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilDeSortie::class, inversedBy="apprenants")
     */
    private $profilDeSorties;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->profilDeSorties = new ArrayCollection();
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    public function getFirstConnexion(): ?bool
    {
        return $this->firstConnexion;
    }

    public function setFirstConnexion(bool $firstConnexion): self
    {
        $this->firstConnexion = $firstConnexion;

        return $this;
    }

    public function getProfilDeSorties(): ?ProfilDeSortie
    {
        return $this->profilDeSorties;
    }

    public function setProfilDeSorties(?ProfilDeSortie $profilDeSorties): self
    {
        $this->profilDeSorties = $profilDeSorties;

        return $this;
    }
}
