<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *   attributes={
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
 *  },
 *  collectionOperations = {
 *   "lister_referentiels":{
 *    "method": "GET",
 *    "path": "/admin/referentiels",
 *    "normalization_context"={"groups":"ref:read"},
 *    "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *    "access_control_message"="Vous n'avez pas accès à cette Ressource",
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
 *  itemOperations = {
 *   "afficher_referentiel":{
 *    "method": "GET",
 *    "path": "/admin/referentiels/{id}",
 *    "normalization_context"={"groups":"ref:read"},
 *    "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *    "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *  },
 *  "afficher_groupecompetence_referentiel":{ 
 *   "method": "GET",
 *   "path": "/admin/referentiels/{id}/grpecompetences/{id2}",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *   "route_name" = "lister_competences",
 *  },
 *  "update_referentiel":{
 *   "method": "PUT",
 *   "path": "/admin/referentiels/{id}",
 *   "normalization_context"={"groups":"referentiel:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas accès à cette Ressource",
 *   "route_name" = "modifier_referentiel",
 *  },
 *  },
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read", "ref:read", "promo:write", "promoref:read", "promo:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "ref:read", "promoref:read", "promo:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels", cascade={"persist"})
     * @ApiSubresource()
     * @Groups({"referentiel:read"})
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel:read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob")
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read"})
     */
    private $critereAdmission;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="referentiels")
     */
    private $promos;

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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme()
    {
        return stream_get_contents($this->programme);
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

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
            $promo->addReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeReferentiel($this);
        }

        return $this;
    }
}
