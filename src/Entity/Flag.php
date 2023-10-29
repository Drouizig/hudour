<?php

namespace App\Entity;

use App\Entity\Enum\FlagType;
use App\Repository\FlagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlagRepository::class)]
class Flag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: FlagType::class)]
    private ?FlagType $type = null;

    #[ORM\OneToMany(mappedBy: 'flag', targetEntity: Affix::class, orphanRemoval: true)]
    private Collection $affixes;

    #[ORM\ManyToMany(targetEntity: Word::class, inversedBy: 'flags')]
    private Collection $words;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->affixes = new ArrayCollection();
        $this->words = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Affix>
     */
    public function getAffixes(): Collection
    {
        return $this->affixes;
    }

    public function addAffix(Affix $affix): static
    {
        if (!$this->affixes->contains($affix)) {
            $this->affixes->add($affix);
            $affix->setFlag($this);
        }

        return $this;
    }

    public function removeAffix(Affix $affix): static
    {
        if ($this->affixes->removeElement($affix)) {
            // set the owning side to null (unless already changed)
            if ($affix->getFlag() === $this) {
                $affix->setFlag(null);
            }
        }

        return $this;
    }


    public function getType(): ?FlagType
    {
        return $this->type;
    }

    public function setType(FlagType $type): static
    {
        $this->type = $type;

        return $this;
    }
    /**
     * @return Collection<int, Word>
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): static
    {
        if (!$this->words->contains($word)) {
            $this->words->add($word);
        }

        return $this;
    }

    public function removeWord(Word $word): static
    {
        $this->words->removeElement($word);

        return $this;
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

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function __toString(): string
    {
        return $this->getName();
    }


}
