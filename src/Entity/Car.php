<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use DateTimeTrait;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registration_number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $variant = null;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

    #[ORM\Column(nullable: true)]
    private ?float $baseprice = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Ride::class)]
    private Collection $rides;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?Proprietaire $propretaire = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Image::class)]
    private Collection $images;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?GpsDevice $gpsdevice = null;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registration_number;
    }

    public function setRegistrationNumber(?string $registration_number): self
    {
        $this->registration_number = $registration_number;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function setVariant(?string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getBaseprice(): ?float
    {
        return $this->baseprice;
    }

    public function setBaseprice(?float $baseprice): self
    {
        $this->baseprice = $baseprice;

        return $this;
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): self
    {
        if (!$this->rides->contains($ride)) {
            $this->rides->add($ride);
            $ride->setCar($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getCar() === $this) {
                $ride->setCar(null);
            }
        }

        return $this;
    }

    public function getPropretaire(): ?Proprietaire
    {
        return $this->propretaire;
    }

    public function setPropretaire(?Proprietaire $propretaire): self
    {
        $this->propretaire = $propretaire;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setCar($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getCar() === $this) {
                $image->setCar(null);
            }
        }

        return $this;
    }

    public function getGpsdevice(): ?GpsDevice
    {
        return $this->gpsdevice;
    }

    public function setGpsdevice(?GpsDevice $gpsdevice): self
    {
        $this->gpsdevice = $gpsdevice;

        return $this;
    }
}
