<?php

namespace App\Entity;

use App\Repository\StemmenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StemmenRepository::class)]
#[ORM\Table(name: 'Stemmen')]
#[ORM\UniqueConstraint(name: 'unique_vraag_stem', columns: ['Profiel_ID', 'Vraag_ID'])]
#[ORM\UniqueConstraint(name: 'unique_antwoord_stem', columns: ['Profiel_ID', 'Antwoord_ID'])]
class Stemmen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Stem_ID')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Profiel::class)]
    #[ORM\JoinColumn(name: 'Profiel_ID', referencedColumnName: 'Profiel_ID', nullable: false)]
    private ?Profiel $profiel = null;

    #[ORM\ManyToOne(targetEntity: Vragen::class)]
    #[ORM\JoinColumn(name: 'Vraag_ID', referencedColumnName: 'Vraag_ID', nullable: true)]
    private ?Vragen $vraag = null;

    #[ORM\ManyToOne(targetEntity: Antwoorden::class)]
    #[ORM\JoinColumn(name: 'Antwoord_ID', referencedColumnName: 'Antwoorden_ID', nullable: true)]
    private ?Antwoorden $antwoord = null;

    #[ORM\Column(name: 'Type', length: 4)]
    private ?string $type = null;

    public function getId(): ?int { return $this->id; }

    public function getProfiel(): ?Profiel { return $this->profiel; }
    public function setProfiel(?Profiel $profiel): static { $this->profiel = $profiel; return $this; }

    public function getVraag(): ?Vragen { return $this->vraag; }
    public function setVraag(?Vragen $vraag): static { $this->vraag = $vraag; return $this; }

    public function getAntwoord(): ?Antwoorden { return $this->antwoord; }
    public function setAntwoord(?Antwoorden $antwoord): static { $this->antwoord = $antwoord; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }
}
