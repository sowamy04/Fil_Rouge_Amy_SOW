<?php

namespace App\Entity;

use App\Entity\Promo;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *  attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}},
 *  },
 *  collectionOperations = {
 *   "lister_groupes":{ 
 *   "method": "GET",
 *   "path": "/admin/groupes",
 *   "normalization_context"={"groups":"grpe:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_apprennant_groupe":{
 *   "method": "GET",
 *   "path": "/admin/groupes/apprenants",
 *   "normalization_context"={"groups":"groupe:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "add_groupe":{
 *   "method": "POST",
 *   "path": "/admin/groupes",
 *   "normalization_context"={"groups":"groupe:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *   "route_name" = "ajouter_groupe",
 *  },
 * },
 *  itemOperations = {
 *   "afficher_groupe":{
 *   "method": "GET",
 *   "path": "/admin/groupes/{id}",
 *   "normalization_context"={"groups":"grpe:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "update_groupe":{
 *   "method": "PUT",
 *   "path": "/admin/groupes/{id}",
 *   "normalization_context"={"groups":"groupe:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *   "route_name" = "mofifier_groupe",
 *  },
 *  "remove_apprenant_groupe":{
 *   "method": "DELETE",
 *   "path": "/admin/groupes/{id}/apprenants/{id2}",
 *   "normalization_context"={"groups":"groupe:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  }
 * )
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"groupe:read", "promo:write", "grpe:read", "promoform:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe:read", "grpe:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes", cascade={"persist"})
     * @Groups({"groupe:read", "promo:write"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes", cascade={"persist"})
     * @Groups({"promoform:read"})
     */
    private $formateurs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPromos(): ?Promo
    {
        return $this->promos;
    }

    public function setPromos(?Promo $promos): self 
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
