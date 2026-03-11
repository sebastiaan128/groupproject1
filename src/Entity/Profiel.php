<?php

namespace App\Entity;

use App\Repository\ProfielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfielRepository::class)]
#[ORM\Table(name: 'Profiel')]
class Profiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Profiel_ID')]
    private ?int $id = null;

    #[ORM\Column(name: 'Name', length: 50)]
    private ?string $name = null;

    #[ORM\Column(name: 'Email', length: 255)]
    private ?string $email = null;

    #[ORM\Column(name: 'Studie', length: 50, nullable: true)]
    private ?string $studie = null;

    #[ORM\Column(name: 'Jaar')]
    private ?int $jaar = null;

    #[ORM\Column(name: 'Bio', length: 250)]
    private ?string $bio = null;

    #[ORM\Column(name: 'FirebaseUid', length: 128, unique: true, nullable: true)]
    private ?string $firebaseUid = null;

    #[ORM\Column(name: 'CreatedAt', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, Vragen>
     */
    #[ORM\OneToMany(targetEntity: Vragen::class, mappedBy: 'profiel', cascade: ['remove'])]
    private Collection $vragen;

    /**
     * @var Collection<int, Antwoorden>
     */
    #[ORM\OneToMany(targetEntity: Antwoorden::class, mappedBy: 'profiel', cascade: ['remove'])]
    private Collection $antwoorden;

    /**
     * @var Collection<int, Tags>
     */
    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'profielen', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'Profiel_Tags')]
    #[ORM\JoinColumn(name: 'Profiel_ID', referencedColumnName: 'Profiel_ID')]
    #[ORM\InverseJoinColumn(name: 'Tags_ID', referencedColumnName: 'Tags_ID')]
    private Collection $tags;

    public function __construct()
    {
        $this->vragen = new ArrayCollection();
        $this->antwoorden = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getStudie(): ?string
    {
        return $this->studie;
    }

    public function setStudie(?string $studie): static
    {
        $this->studie = $studie;

        return $this;
    }

    public function getJaar(): ?int
    {
        return $this->jaar;
    }

    public function setJaar(int $jaar): static
    {
        $this->jaar = $jaar;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): static
    {
        $this->bio = $bio;

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
            $vraag->setProfiel($this);
        }

        return $this;
    }

    public function removeVraag(Vragen $vraag): static
    {
        if ($this->vragen->removeElement($vraag)) {
            if ($vraag->getProfiel() === $this) {
                $vraag->setProfiel(null);
            }
        }

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
            $antwoord->setProfiel($this);
        }

        return $this;
    }

    public function removeAntwoord(Antwoorden $antwoord): static
    {
        if ($this->antwoorden->removeElement($antwoord)) {
            if ($antwoord->getProfiel() === $this) {
                $antwoord->setProfiel(null);
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

    public function getFirebaseUid(): ?string
    {
        return $this->firebaseUid;
    }

    public function setFirebaseUid(string $firebaseUid): static
    {
        $this->firebaseUid = $firebaseUid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

