<?php

namespace App\Entity;

use App\Entity\Enum\AffixType;
use App\Repository\AffixRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffixRepository::class)]
class Affix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $stripping = null;

    #[ORM\Column(length: 255)]
    private ?string $prefix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $condition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $morphologicalFields = null;

    #[ORM\ManyToOne(inversedBy: 'affix')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Flag $flag = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStripping(): ?string
    {
        return $this->stripping;
    }

    public function setStripping(string $stripping): static
    {
        $this->stripping = $stripping;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getMorphologicalFields(): ?string
    {
        return $this->morphologicalFields;
    }

    public function setMorphologicalFields(?string $morphologicalFields): static
    {
        $this->morphologicalFields = $morphologicalFields;

        return $this;
    }

    public function getFlag(): ?Flag
    {
        return $this->flag;
    }

    public function setFlag(?Flag $flag): static
    {
        $this->flag = $flag;

        return $this;
    }
}
