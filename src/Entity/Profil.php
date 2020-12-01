<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 * collectionOperations={
 *  "lister_profils" = {
 *     "method" : "GET",
 *      "path":"admin/profils",
 *      "normalization_context"={"groups":"profil:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * 
 *  "ajouter_profil" = {
 *     "method" : "POST",
 *      "path":"admin/profils",
 *      "normalization_context"={"groups":"profil:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * },
 * itemOperations={
 *  "afficher_profil" = {
 *     "method" : "GET",
 *      "path":"admin/profils/{id}",
 *      "normalization_context"={"groups":"profil:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "modifier_profil" = {
 *     "method" : "PUT",
 *      "path":"admin/profils/{id}",
 *      "normalization_context"={"groups":"profil:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "archiver_profil" = {
 *     "method" : "DELETE",
 *      "path":"admin/profils/{id}",
 *      "normalization_context"={"groups":"profil:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *  "lister_utilisateurs_profil" = {
 *     "method" : "GET",
 *      "path":"admin/profils/{id}/users",
 *      "normalization_context"={"groups":"profilUser:read"},
 *      "access_control"="(is_granted('ROLE_ADMIN'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isExisting"})
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil:read", "profilUser:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read", "profilUser:read", "user:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profils")
     * @Groups({"profilUser:read"})
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"profil:read"})
     */
    private $isExisting;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfils($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfils() === $this) {
                $user->setProfils(null);
            }
        }

        return $this;
    }

    public function getIsExisting(): ?bool
    {
        return $this->isExisting;
    }

    public function setIsExisting(bool $isExisting): self
    {
        $this->isExisting = $isExisting;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}
