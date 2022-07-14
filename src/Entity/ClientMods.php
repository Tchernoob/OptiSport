<?php

namespace App\Entity;

use App\Repository\ClientModsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientModsRepository::class)]
class ClientMods
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\ManyToMany(targetEntity: Partner::class, mappedBy: 'modules')]
    private Collection $partners;

    #[ORM\ManyToMany(targetEntity: Structure::class, mappedBy: 'modules')]
    private Collection $structures;

    #[ORM\ManyToMany(targetEntity: Mods::class, mappedBy: 'clients')]
    private Collection $mods;

    public function __construct()
    {
        $this->partners = new ArrayCollection();
        $this->structures = new ArrayCollection();
        $this->mods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Partner>
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners[] = $partner;
            $partner->addModule($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partners->removeElement($partner)) {
            $partner->removeModule($this);
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
            $structure->addModule($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->removeElement($structure)) {
            $structure->removeModule($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Mods>
     */
    public function getMods(): Collection
    {
        return $this->mods;
    }

    public function addMod(Mods $mod): self
    {
        if (!$this->mods->contains($mod)) {
            $this->mods[] = $mod;
            $mod->addClient($this);
        }

        return $this;
    }

    public function removeMod(Mods $mod): self
    {
        if ($this->mods->removeElement($mod)) {
            $mod->removeClient($this);
        }

        return $this;
    }
}
