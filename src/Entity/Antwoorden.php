<?php

namespace App\Entity;

use App\Repository\AntwoordenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AntwoordenRepository::class)]
#[ORM\Table(name: 'Antwoorden')]
class Antwoorden
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Antwoorden_ID')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Vragen::class, inversedBy: 'antwoorden')]
    #[ORM\JoinColumn(name: 'Vraag_ID', referencedColumnName: 'Vraag_ID', nullable: false)]
    private ?Vragen $vraag = null;

    #[ORM\ManyToOne(targetEntity: Profiel::class, inversedBy: 'antwoorden')]
    #[ORM\JoinColumn(name: 'Profiel_ID', referencedColumnName: 'Profiel_ID', nullable: false)]
    private ?Profiel $profiel = null;

    #[ORM\Column(name: 'Beschrijving', length: 250, type: Types::TEXT)]
    private ?string $beschrijving = null;

    #[ORM\Column(name: 'Upvotes', nullable: true)]
    private ?int $upvotes = null;

    #[ORM\Column(name: 'Downvotes', nullable: true)]
    private ?int $downvotes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVraag(): ?Vragen
    {
        return $this->vraag;
    }

    public function setVraag(?Vragen $vraag): static
    {
        $this->vraag = $vraag;

        return $this;
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

    public function setDownvotes(?int $downvotes): static
    {
        $this->downvotes = $downvotes;

        return $this;
    }
}
