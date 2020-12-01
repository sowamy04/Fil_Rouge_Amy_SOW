<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @ApiResource(
 * attributes={
 *      "input_formats"={"json"={"application/ld+json", "application/json"}},
 *      "output_formats"={"json"={"application/ld+json", "application/json"}},
 * },
 * itemOperations={
 * "get_tags":{
 *   "method": "GET",
 *   "path": "/admin/tags/{id}",
 *   "normalization_context"={"groups":"tags:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 * "update_tag":{
 *   "method": "PUT",
 *   "path": "/admin/tags/{id}",
 *   "normalization_context"={"groups":"tags:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 * "remove_tag":{
 *   "method": "DELETE",
 *   "path": "/admin/tags/{id}",
 *   "normalization_context"={"groups":"tags:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 * },
 * },
 * collectionOperations={
 * "get_tags": {
 *   "method": "GET",
 *   "path": "/admin/tags",
 *   "normalization_context"={"groups":"tags:read"},
 *   "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 * "add_tag": {
 *    "method": "POST",
 *    "path": "/admin/tags",
 *    "normalization_context"={"groups":"tags:read"},
 *    "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *    "access_control_message"="Vous n'avez pas access à cette Ressource",
 *   }
 * }
 * )
 */
class Tags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tags:read", "grpetags:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tags:read", "grpetags:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTags::class, mappedBy="tags")
     * @Groups({"tags:read"})
     */
    private $groupeTags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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
     * @return Collection|GroupeTags[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTags $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTags $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

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
}
