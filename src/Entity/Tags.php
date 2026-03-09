<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
#[ORM\Table(name: 'Tags')]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Tags_ID')]
    private ?int $id = null;

    #[ORM\Column(name: 'naam', length: 50)]
    private ?string $naam = null;

    /**
     * @var Collection<int, Profiel>
     */
    #[ORM\ManyToMany(targetEntity: Profiel::class, mappedBy: 'tags')]
    private Collection $profielen;

    /**
     * @var Collection<int, Vragen>
     */
    #[ORM\ManyToMany(targetEntity: Vragen::class, mappedBy: 'tags')]
    private Collection $vragen;

    public function __construct()
    {
        $this->profielen = new ArrayCollection();
        $this->vragen = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): static
    {
        $this->naam = $naam;

        return $this;
    }

    /**
     * @return Collection<int, Profiel>
     */
    public function getProfielen(): Collection
    {
        return $this->profielen;
    }

    public function addProfiel(Profiel $profiel): static
    {
        if (!$this->profielen->contains($profiel)) {
            $this->profielen->add($profiel);
            $profiel->addTag($this);
        }

        return $this;
    }

    public function removeProfiel(Profiel $profiel): static
    {
        if ($this->profielen->removeElement($profiel)) {
            $profiel->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Vragen>
     */
    public function getVragen(): Collection
    {
        return $this->vragen;
    }

    public function addVraag(Vragen $vraag): static
    {
        if (!$this->vragen->contains($vraag)) {
            $this->vragen->add($vraag);
            $vraag->addTag($this);
        }

        return $this;
    }

    public function removeVraag(Vragen $vraag): static
    {
        if ($this->vragen->removeElement($vraag)) {
            $vraag->removeTag($this);
        }

        return $this;
    }
}
