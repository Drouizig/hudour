<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordRepository::class)]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $word = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $morphologicalFields = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToMany(targetEntity: Flag::class, mappedBy: 'words')]
    private Collection $flags;

    public function __construct()
    {
        $this->flags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): static
    {
        $this->word = $word;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, Flag>
     */
    public function getFlags(): Collection
    {
        return $this->flags;
    }

    public function addFlag(Flag $flag): static
    {
        if (!$this->flags->contains($flag)) {
            $this->flags->add($flag);
            $flag->addWord($this);
        }

        return $this;
    }

    public function removeFlag(Flag $flag): static
    {
        if ($this->flags->removeElement($flag)) {
            $flag->removeWord($this);
        }

        return $this;
    }
}
