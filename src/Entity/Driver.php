<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
use DateTimeTrait;
    #[ORM\Column]
    private ?bool $status = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $compte = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $licence = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cni = null;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Ride::class)]
    private Collection $rides;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\Column(length: 255)]
    private ?string $permitdriver = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expiratedPermit = null;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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
    public function getLicence(): ?string
    {
        return $this->licence;
    }

    public function setLicence(?string $licence): self
    {
        $this->licence = $licence;

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
            $ride->setDriver($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getDriver() === $this) {
                $ride->setDriver(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->compte->getName();
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPermitdriver(): ?string
    {
        return $this->permitdriver;
    }

    public function setPermitdriver(string $permitdriver): self
    {
        $this->permitdriver = $permitdriver;

        return $this;
    }

    public function getExpiratedPermit(): ?\DateTimeInterface
    {
        return $this->expiratedPermit;
    }

    public function setExpiratedPermit(?\DateTimeInterface $expiratedPermit): self
    {
        $this->expiratedPermit = $expiratedPermit;

        return $this;
    }

}
