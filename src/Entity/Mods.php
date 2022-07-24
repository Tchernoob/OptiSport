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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToMany(targetEntity: TemplateMods::class, inversedBy: 'mods')]
    private Collection $template;

    #[ORM\ManyToMany(targetEntity: ClientMods::class, inversedBy: 'mods')]
    private Collection $clients;

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
     * @return Collection<int, ClientMods>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(ClientMods $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
        }

        return $this;
    }

    public function removeClient(ClientMods $client): self
    {
        $this->clients->removeElement($client);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
