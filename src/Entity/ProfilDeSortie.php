<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilDeSortieRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilDeSortieRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *  "lister_profilsorties" = {
 *     "method" : "GET",
 *      "path":"admin/profilsorties",
 *      "normalization_context"={"groups":"profil_sorties:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  }
 * },
 * itemOperations={
 * "archiver_profil_sorties" = {
 *     "method" : "DELETE",
 *      "path":"admin/profilsorties/{id}",
 *      "normalization_context"={"groups":"profil:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_profilSorties_promo" = {
 *     "method" : "GET",
 *      "path":"admin/promos/{id}/profilsorties",
 *      "normalization_context"={"groups":"profilUser:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * }
 * )
 */
class ProfilDeSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil_sorties:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil_sorties:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, inversedBy="profilDeSorties")
     * @Groups({"profil_sorties:read"})
     */
    private $promo;

    public function __construct()
    {
        $this->promo = new ArrayCollection();
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
    public function getPromo(): Collection
    {
        return $this->promo;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promo->contains($promo)) {
            $this->promo[] = $promo;
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        $this->promo->removeElement($promo);

        return $this;
    }
}
