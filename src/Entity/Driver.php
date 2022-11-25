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
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ipaddress = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $callid = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?int $lac = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $radiotype = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mobilenetworkcode= null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mobilenetcode = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $countrycode = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $carrier = null;
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
    public function getIpaddress(): ?string
    {
        return $this->ipaddress;
    }

    /**
     * @param string|null $ipaddress
     */
    public function setIpaddress(?string $ipaddress): void
    {
        $this->ipaddress = $ipaddress;
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

    /**
     * @return string|null
     */
    public function getCallid(): ?string
    {
        return $this->callid;
    }

    /**
     * @param string|null $callid
     */
    public function setCallid(?string $callid): void
    {
        $this->callid = $callid;
    }

    /**
     * @return int|null
     */
    public function getLac(): ?int
    {
        return $this->lac;
    }

    /**
     * @param int|null $lac
     */
    public function setLac(?int $lac): void
    {
        $this->lac = $lac;
    }

    /**
     * @return string|null
     */
    public function getRadiotype(): ?string
    {
        return $this->radiotype;
    }

    /**
     * @param string|null $radiotype
     */
    public function setRadiotype(?string $radiotype): void
    {
        $this->radiotype = $radiotype;
    }

    /**
     * @return string|null
     */
    public function getMobilenetworkcode(): ?string
    {
        return $this->mobilenetworkcode;
    }

    /**
     * @param string|null $mobilenetworkcode
     */
    public function setMobilenetworkcode(?string $mobilenetworkcode): void
    {
        $this->mobilenetworkcode = $mobilenetworkcode;
    }

    /**
     * @return string|null
     */
    public function getMobilenetcode(): ?string
    {
        return $this->mobilenetcode;
    }

    /**
     * @param string|null $mobilenetcode
     */
    public function setMobilenetcode(?string $mobilenetcode): void
    {
        $this->mobilenetcode = $mobilenetcode;
    }

    /**
     * @return string|null
     */
    public function getCountrycode(): ?string
    {
        return $this->countrycode;
    }

    /**
     * @param string|null $countrycode
     */
    public function setCountrycode(?string $countrycode): void
    {
        $this->countrycode = $countrycode;
    }

    /**
     * @return string|null
     */
    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

    /**
     * @param string|null $carrier
     */
    public function setCarrier(?string $carrier): void
    {
        $this->carrier = $carrier;
    }

}
