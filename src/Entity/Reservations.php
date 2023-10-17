<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Boardgame $game = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $rental = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan('today UTC')]
    protected \DateTimeInterface $start_rent;
   
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan(propertyPath:'start_rent')]
    protected \DateTimeInterface $end_rent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Boardgame
    {
        return $this->game;
    }

    public function setGame(?Boardgame $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getRental(): ?User
    {
        return $this->rental;
    }

    public function setRental(?User $rental): static
    {
        $this->rental = $rental;

        return $this;
    }

    public function getStartRent(): ?\DateTimeInterface
    {
        return $this->start_rent;
    }

    public function setStartRent(\DateTimeInterface $start_rent): static
    {
        $this->start_rent = $start_rent;

        return $this;
    }

    public function getEndRent(): ?\DateTimeInterface
    {
        return $this->end_rent;
    }

    public function setEndRent(\DateTimeInterface $end_rent): static
    {
        $this->end_rent = $end_rent;

        return $this;
    }

    public function __toString() {
        return $this->getRental(); // Utilisez une propriété pertinente de votre entité.
    }
}
