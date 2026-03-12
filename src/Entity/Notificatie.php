<?php

namespace App\Entity;

use App\Repository\NotificatieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificatieRepository::class)]
#[ORM\Table(name: 'Notificatie')]
class Notificatie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Notificatie_ID')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Profiel::class)]
    #[ORM\JoinColumn(name: 'Profiel_ID', referencedColumnName: 'Profiel_ID', nullable: false, onDelete: 'CASCADE')]
    private ?Profiel $profiel = null;

    #[ORM\ManyToOne(targetEntity: Vragen::class)]
    #[ORM\JoinColumn(name: 'Vraag_ID', referencedColumnName: 'Vraag_ID', nullable: false, onDelete: 'CASCADE')]
    private ?Vragen $vraag = null;

    #[ORM\Column(name: 'Bericht', length: 255)]
    private ?string $bericht = null;

    #[ORM\Column(name: 'IsGelezen', type: 'boolean', options: ['default' => false])]
    private bool $isGelezen = false;

    #[ORM\Column(name: 'AangemaaktOp', type: 'datetime')]
    private ?\DateTimeInterface $aangemaaktOp = null;

    public function getId(): ?int { return $this->id; }

    public function getProfiel(): ?Profiel { return $this->profiel; }
    public function setProfiel(?Profiel $profiel): static { $this->profiel = $profiel; return $this; }

    public function getVraag(): ?Vragen { return $this->vraag; }
    public function setVraag(?Vragen $vraag): static { $this->vraag = $vraag; return $this; }

    public function getBericht(): ?string { return $this->bericht; }
    public function setBericht(string $bericht): static { $this->bericht = $bericht; return $this; }

    public function isGelezen(): bool { return $this->isGelezen; }
    public function setIsGelezen(bool $isGelezen): static { $this->isGelezen = $isGelezen; return $this; }

    public function getAangemaaktOp(): ?\DateTimeInterface { return $this->aangemaaktOp; }
    public function setAangemaaktOp(\DateTimeInterface $aangemaaktOp): static { $this->aangemaaktOp = $aangemaaktOp; return $this; }
}
