<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $javascript = null;

    #[ORM\Column]
    private ?bool $html = null;

    #[ORM\Column]
    private ?bool $css = null;

    #[ORM\Column]
    private ?bool $mysql = null;

    #[ORM\Column]
    private ?bool $cplus = null;

    #[ORM\Column]
    private ?bool $c = null;

    #[ORM\Column]
    private ?bool $csharp = null;

    #[ORM\Column]
    private ?bool $java = null;

    #[ORM\Column]
    private ?bool $php = null;

    #[ORM\Column]
    private ?bool $typescript = null;

    #[ORM\Column]
    private ?bool $nodejs = null;

    #[ORM\Column]
    private ?bool $laravel = null;

    #[ORM\Column]
    private ?bool $react = null;

    #[ORM\Column]
    private ?bool $python = null;

    #[ORM\Column]
    private ?bool $symfony = null;

    #[ORM\Column]
    private ?bool $scss = null;

    #[ORM\Column]
    private ?bool $bootstrap = null;

    #[ORM\Column]
    private ?bool $tailwind = null;

    #[ORM\Column]
    private ?bool $rust = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isJavascript(): ?bool
    {
        return $this->javascript;
    }

    public function setJavascript(bool $javascript): static
    {
        $this->javascript = $javascript;

        return $this;
    }

    public function isHtml(): ?bool
    {
        return $this->html;
    }

    public function setHtml(bool $html): static
    {
        $this->html = $html;

        return $this;
    }

    public function isCss(): ?bool
    {
        return $this->css;
    }

    public function setCss(bool $css): static
    {
        $this->css = $css;

        return $this;
    }

    public function isMysql(): ?bool
    {
        return $this->mysql;
    }

    public function setMysql(bool $mysql): static
    {
        $this->mysql = $mysql;

        return $this;
    }

    public function isCplus(): ?bool
    {
        return $this->cplus;
    }

    public function setCplus(bool $cplus): static
    {
        $this->cplus = $cplus;

        return $this;
    }

    public function isC(): ?bool
    {
        return $this->c;
    }

    public function setC(bool $c): static
    {
        $this->c = $c;

        return $this;
    }

    public function isCsharp(): ?bool
    {
        return $this->csharp;
    }

    public function setCsharp(bool $csharp): static
    {
        $this->csharp = $csharp;

        return $this;
    }

    public function isJava(): ?bool
    {
        return $this->java;
    }

    public function setJava(bool $java): static
    {
        $this->java = $java;

        return $this;
    }

    public function isPhp(): ?bool
    {
        return $this->php;
    }

    public function setPhp(bool $php): static
    {
        $this->php = $php;

        return $this;
    }

    public function isTypescript(): ?bool
    {
        return $this->typescript;
    }

    public function setTypescript(bool $typescript): static
    {
        $this->typescript = $typescript;

        return $this;
    }

    public function isNodejs(): ?bool
    {
        return $this->nodejs;
    }

    public function setNodejs(bool $nodejs): static
    {
        $this->nodejs = $nodejs;

        return $this;
    }

    public function isLaravel(): ?bool
    {
        return $this->laravel;
    }

    public function setLaravel(bool $laravel): static
    {
        $this->laravel = $laravel;

        return $this;
    }

    public function isReact(): ?bool
    {
        return $this->react;
    }

    public function setReact(bool $react): static
    {
        $this->react = $react;

        return $this;
    }

    public function isPython(): ?bool
    {
        return $this->python;
    }

    public function setPython(bool $python): static
    {
        $this->python = $python;

        return $this;
    }

    public function isSymfony(): ?bool
    {
        return $this->symfony;
    }

    public function setSymfony(bool $symfony): static
    {
        $this->symfony = $symfony;

        return $this;
    }

    public function isScss(): ?bool
    {
        return $this->scss;
    }

    public function setScss(bool $scss): static
    {
        $this->scss = $scss;

        return $this;
    }

    public function isBootstrap(): ?bool
    {
        return $this->bootstrap;
    }

    public function setBootstrap(bool $bootstrap): static
    {
        $this->bootstrap = $bootstrap;

        return $this;
    }

    public function isTailwind(): ?bool
    {
        return $this->tailwind;
    }

    public function setTailwind(bool $tailwind): static
    {
        $this->tailwind = $tailwind;

        return $this;
    }

    public function isRust(): ?bool
    {
        return $this->rust;
    }

    public function setRust(bool $rust): static
    {
        $this->rust = $rust;

        return $this;
    }
}
