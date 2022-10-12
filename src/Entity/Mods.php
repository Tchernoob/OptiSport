<?php

namespace App\Entity;

use App\Repository\ModsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModsRepository::class)]
class Mods
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToMany(targetEntity: TemplateMods::class, inversedBy: 'mods')]
    private Collection $template;

    #[ORM\ManyToMany(targetEntity: Partner::class, mappedBy: 'mods')]
    private $partners;

    #[ORM\ManyToMany(targetEntity: Structure::class, mappedBy: 'mods')]
    private $structures;


    public function __construct()
    {
        $this->template = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, TemplateMods>
     */
    public function getTemplate(): Collection
    {
        return $this->template;
    }

    public function addTemplate(TemplateMods $template): self
    {
        if (!$this->template->contains($template)) {
            $this->template[] = $template;
        }

        return $this;
    }

    public function removeTemplate(TemplateMods $template): self
    {
        $this->template->removeElement($template);

        return $this;
    }

    /**
     * @return Collection<int, Partner>
     */
    public function getPartners(): ?Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners[] = $partner;
            $partner->addMods($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partner->removeElement($partner)) {
            $partner->removeMods($this);
        }

        return $this;
    }

        /**
     * @return Collection<int, Structure>
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->addMod($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structure->removeElement($structure)) {
            $structure->removeMod($this);
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }    
}