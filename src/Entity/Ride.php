<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    const PENDING="PENDING";
    const ACCEPTED="ACCEPTED";
    const REJECT="REJECT";
    const STARTING="STARTING";
    const FINISH="FINISH";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use DateTimeTrait;
    #[ORM\ManyToOne(inversedBy: 'rides')]
    private ?Driver $driver = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    private ?Car $car = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $pikupbegin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $pickupend = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $startto = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endto = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $distance = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?int $estimateTime = 0;
    #[ORM\Column(nullable: true)]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitudestart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitudestart = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitudestop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitudestop = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getPikupbegin(): ?\DateTimeInterface
    {
        return $this->pikupbegin;
    }

    public function setPikupbegin(?\DateTimeInterface $pikupbegin): self
    {
        $this->pikupbegin = $pikupbegin;

        return $this;
    }

    public function getPickupend(): ?\DateTimeInterface
    {
        return $this->pickupend;
    }

    public function setPickupend(?\DateTimeInterface $pickupend): self
    {
        $this->pickupend = $pickupend;

        return $this;
    }

    public function getStartto(): ?string
    {
        return $this->startto;
    }

    public function setStartto(?string $startto): self
    {
        $this->startto = $startto;

        return $this;
    }

    public function getEndto(): ?string
    {
        return $this->endto;
    }

    public function setEndto(?string $endto): self
    {
        $this->endto = $endto;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDistance(): ?string
    {
        return $this->distance;
    }

    /**
     * @param string|null $distance
     */
    public function setDistance(?string $distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return string|null
     */
    public function getLongitudestart(): ?string
    {
        return $this->longitudestart;
    }

    /**
     * @param string|null $longitudestart
     */
    public function setLongitudestart(?string $longitudestart): void
    {
        $this->longitudestart = $longitudestart;
    }

    /**
     * @return string|null
     */
    public function getLatitudestart(): ?string
    {
        return $this->latitudestart;
    }

    /**
     * @param string|null $latitudestart
     */
    public function setLatitudestart(?string $latitudestart): void
    {
        $this->latitudestart = $latitudestart;
    }

    /**
     * @return string|null
     */
    public function getLongitudestop(): ?string
    {
        return $this->longitudestop;
    }

    /**
     * @param string|null $longitudestop
     */
    public function setLongitudestop(?string $longitudestop): void
    {
        $this->longitudestop = $longitudestop;
    }

    /**
     * @return string|null
     */
    public function getLatitudestop(): ?string
    {
        return $this->latitudestop;
    }

    /**
     * @param string|null $latitudestop
     */
    public function setLatitudestop(?string $latitudestop): void
    {
        $this->latitudestop = $latitudestop;
    }

    /**
     * @return int|null
     */
    public function getEstimateTime(): ?int
    {
        return $this->estimateTime;
    }

    /**
     * @param int|null $estimateTime
     */
    public function setEstimateTime(?int $estimateTime): void
    {
        $this->estimateTime = $estimateTime;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
