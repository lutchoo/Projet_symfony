<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Boardgame::class)]
    private Collection $boardgames;

    public function __construct()
    {
        $this->boardgames = new ArrayCollection();
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

    /**
     * @return Collection<int, Boardgame>
     */
    public function getBoardgames(): Collection
    {
        return $this->boardgames;
    }

    public function addBoardgame(Boardgame $boardgame): static
    {
        if (!$this->boardgames->contains($boardgame)) {
            $this->boardgames->add($boardgame);
            $boardgame->setCategorie($this);
        }

        return $this;
    }

    public function removeBoardgame(Boardgame $boardgame): static
    {
        if ($this->boardgames->removeElement($boardgame)) {
            // set the owning side to null (unless already changed)
            if ($boardgame->getCategorie() === $this) {
                $boardgame->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->getName(); // Utilisez une propriété pertinente de votre entité.
    }
}
