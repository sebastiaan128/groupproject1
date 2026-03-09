<?php

namespace App\Entity;

use App\Repository\VragenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VragenRepository::class)]
#[ORM\Table(name: 'Vragen')]
class Vragen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Vraag_ID')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Profiel::class, inversedBy: 'vragen')]
    #[ORM\JoinColumn(name: 'Profiel_ID', referencedColumnName: 'Profiel_ID', nullable: false)]
    private ?Profiel $profiel = null;

    #[ORM\Column(name: 'Titel', length: 50)]
    private ?string $titel = null;

    #[ORM\Column(name: 'Beschrijving', type: Types::TEXT)]
    private ?string $beschrijving = null;

    #[ORM\Column(name: 'Upvotes', nullable: true)]
    private ?int $upvotes = null;

    #[ORM\Column(name: 'Downvotes', nullable: true)]
    private ?int $downvotes = null;

    #[ORM\Column(name: 'Views', nullable: true)]
    private ?int $views = null;

    #[ORM\Column(name: 'Status', length: 50)]
    private ?string $status = null;

    /**
     * @var Collection<int, Antwoorden>
     */
    #[ORM\OneToMany(targetEntity: Antwoorden::class, mappedBy: 'vraag', cascade: ['remove'])]
    private Collection $antwoorden;

    /**
     * @var Collection<int, Tags>
     */
    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'vragen', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'Vraag_Tags')]
    #[ORM\JoinColumn(name: 'Vraag_ID', referencedColumnName: 'Vraag_ID')]
    #[ORM\InverseJoinColumn(name: 'Tags_ID', referencedColumnName: 'Tags_ID')]
    private Collection $tags;

    public function __construct()
    {
        $this->antwoorden = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfiel(): ?Profiel
    {
        return $this->profiel;
    }

    public function setProfiel(?Profiel $profiel): static
    {
        $this->profiel = $profiel;

        return $this;
    }

    public function getTitel(): ?string
    {
        return $this->titel;
    }

    public function setTitel(string $titel): static
    {
        $this->titel = $titel;

        return $this;
    }

    public function getBeschrijving(): ?string
    {
        return $this->beschrijving;
    }

    public function setBeschrijving(string $beschrijving): static
    {
        $this->beschrijving = $beschrijving;

        return $this;
    }

    public function getUpvotes(): ?int
    {
        return $this->upvotes;
    }

    public function setUpvotes(?int $upvotes): static
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    public function getDownvotes(): ?int
    {
        return $this->downvotes;
    }

    public function setDownvotes(int $downvotes): static
    {
        $this->downvotes = $downvotes;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): static
    {
        $this->views = $views;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Antwoorden>
     */
    public function getAntwoorden(): Collection
    {
        return $this->antwoorden;
    }

    public function addAntwoord(Antwoorden $antwoord): static
    {
        if (!$this->antwoorden->contains($antwoord)) {
            $this->antwoorden->add($antwoord);
            $antwoord->setVraag($this);
        }

        return $this;
    }

    public function removeAntwoord(Antwoorden $antwoord): static
    {
        if ($this->antwoorden->removeElement($antwoord)) {
            if ($antwoord->getVraag() === $this) {
                $antwoord->setVraag(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tags $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
