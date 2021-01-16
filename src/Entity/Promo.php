<?php

namespace App\Entity;

use App\Entity\Referentiel;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *   "lister_promos":{ 
 *    "method": "GET",
 *    "path": "/admin/promo",
 *    "normalization_context"={"groups":"promo:read"},
 *    "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *    "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_promo_principal":{
 *   "method": "GET",
 *   "path": "/admin/promo/principal",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * 
 *  "lister_apprennant_attente_promo":{
 *   "method": "GET",
 *   "path": "/admin/promo/apprenants/attente",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "add_promo":{
 *   "method": "POST",
 *   "path": "/admin/promo",
 *   "normalization_context"={"groups":"promo:read"},
 *   "denormalization_context" = {"groups" : "promo:write"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * },
 *  itemOperations = {
 *   "afficher_groupe":{
 *   "method": "GET",
 *   "path": "/admin/promo/{id}",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_groupe_principal_promo":{
 *   "method": "GET",
 *   "path": "/admin/promo/{id}/principal",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_referentiels_promo":{
 *   "method": "GET",
 *   "path": "/admin/promo/{id}/referentiels",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_apprenants_attente_promo":{
 *   "method": "GET",
 *   "path": "/admin/promo/{id}/apprenants/attente",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "lister_apprenants_groupe__promo":{
 *   "method": "GET",
 *   "path": "/admin/promo/{id}/groupe/{id2}/referentiels",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "lister_formateurs_promo":{
 *   "method": "GET",
 *   "path": "/admin/promo/{id}/formateurs",
 *   "normalization_context"={"groups":"promoform:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "update_groupe_referentiel":{
 *   "method": "PUT",
 *   "path": "/admin/promo/{id}/referentiels",
 *   "normalization_context"={"groups":"promoref:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "update_apprenants_promo":{
 *   "method": "PUT",
 *   "path": "/admin/promo/{id}/apprenants",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource", 
 *  },
 *  "lister_formateurs_promo":{
 *   "method": "PUT",
 *   "path": "/admin/promo/{id}/formateurs",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "update_groupe_apprenant":{
 *   "method": "PUT",
 *   "path": "/admin/promo/{id}/groupes/{id2}",
 *   "normalization_context"={"groups":"promo:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  }
 * )
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promoform:read", "promoref:read", "profil_sorties:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:write", "promoform:read", "promoref:read", "profil_sorties:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:write", "promoform:read", "promoref:read", "profil_sorties:read"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promos", cascade={"persist"})
     * @Groups({"promo:write", "promoform:read"})
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos", cascade={"persist"})
     * @Groups({"promo:write", "promoref:read"})
     */
    private $referentiels;

    /**
     * @ORM\ManyToMany(targetEntity=ProfilDeSortie::class, mappedBy="promo")
     */
    private $profilDeSorties;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->profilDeSorties = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

        return $this;
    }

    public function getReferentiels(): ?Referentiel
    {
        return $this->referentiels;
    }

    public function setReferentiels(?Referentiel $referentiels): self
    {
        $this->referentiels = $referentiels;

        return $this;
    }

    /**
     * @return Collection|ProfilDeSortie[]
     */
    public function getProfilDeSorties(): Collection
    {
        return $this->profilDeSorties;
    }

    public function addProfilDeSorty(ProfilDeSortie $profilDeSorty): self
    {
        if (!$this->profilDeSorties->contains($profilDeSorty)) {
            $this->profilDeSorties[] = $profilDeSorty;
            $profilDeSorty->addPromo($this);
        }

        return $this;
    }

    public function removeProfilDeSorty(ProfilDeSortie $profilDeSorty): self
    {
        if ($this->profilDeSorties->removeElement($profilDeSorty)) {
            $profilDeSorty->removePromo($this);
        }

        return $this;
    }
}
