<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use DateTimeTrait;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $compte = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cni = null;
    #[ORM\OneToMany(mappedBy: 'propretaire', targetEntity: Car::class)]
    private Collection $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompte(): ?User
    {
        return $this->compte;
    }

    public function setCompte(?User $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCni(): ?string
    {
        return $this->cni;
    }

    /**
     * @param string|null $cni
     */
    public function setCni(?string $cni): void
    {
        $this->cni = $cni;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setPropretaire($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getPropretaire() === $this) {
                $car->setPropretaire(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->compte->getName();
    }
}
