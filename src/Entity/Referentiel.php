<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *  itemOperations = {
 *   "lister_referentiels":{
 *   "method": "GET",
 *   "path": "/admin/referentiels",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *  },
 *  "lister_groupecompetences_referentiels":{
 *   "method": "GET",
 *   "path": "/admin/referentiels/grpecompetences",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *  },
 *  "add_referentiel":{
 *   "method": "POST",
 *   "path": "/admin/referentiels",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *   "route_name" = "ajouter_referentiel",
 *  },
 * },
 *  collectionOperations = {
 *   "afficher_referentiel":{
 *   "method": "GET",
 *   "path": "/admin/referentiels/{id}",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *  },
 *  "afficher_groupecompetence_referentiel":{ 
 *   "method": "GET",
 *   "path": "/admin/referentiels/{id}/grpecompetences/{id2}",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *  },
 *  "update_referentiel":{
 *   "method": "PUT",
 *   "path": "/admin/referentiels/{id}",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *   "route_name" = "modifier_referentiel"
 *  },
 *  }
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiels")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Groups({"referentiel:read"})
     */
    private $groupeCompetences;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
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

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiels($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiels() === $this) {
                $promo->setReferentiels(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

        return $this;
    }
}
