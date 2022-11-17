<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $total_ride = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $compte = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Ride::class)]
    private Collection $rides;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: AddressShipping::class)]
    private Collection $addresses;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalRide(): ?int
    {
        return $this->total_ride;
    }

    public function setTotalRide(?int $total_ride): self
    {
        $this->total_ride = $total_ride;

        return $this;
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
            $ride->setCustomer($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getCustomer() === $this) {
                $ride->setCustomer(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->compte->getName();
    }

    /**
     * @return Collection<int, AddressShipping>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(AddressShipping $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setCustomer($this);
        }

        return $this;
    }

    public function removeAddress(AddressShipping $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCustomer() === $this) {
                $address->setCustomer(null);
            }
        }

        return $this;
    }
}
