<?php

namespace App\Entity;

use App\Repository\AffectationRideRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationRideRepository::class)]
class AffectationRide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use DateTimeTrait;
    #[ORM\ManyToOne]
    private ?Car $car = null;

    #[ORM\ManyToOne]
    private ?Driver $driver = null;

    #[ORM\Column]
    private ?bool $isEnable = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiredAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCAr(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function isIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTimeImmutable $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }
}
