<?php

namespace App\Entity;

use App\Repository\ProfielRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfielRepository::class)]
class Profiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $studie = null;

    #[ORM\Column]
    private ?int $jaar = null;

    #[ORM\Column(length: 255)]
    private ?string $bio = null;

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
}
