<?php

namespace App\Entity;

use App\Repository\ShippingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingRepository::class)]
class Shipping
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
    #[ORM\ManyToOne]
    private ?AddressShipping $address = null;

    #[ORM\ManyToOne(inversedBy: 'shippings')]
    private ?Place $place = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $distance = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?AddressShipping
    {
        return $this->address;
    }

    public function setAddress(?AddressShipping $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
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

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }
}
