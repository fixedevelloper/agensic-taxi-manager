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
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;
    #[ORM\ManyToOne(inversedBy: 'shippings')]
    private ?Place $place = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $distance = 0;

    #[ORM\ManyToOne]
    private ?Article $article = null;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceshipping = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude_start = null;

    #[ORM\Column(nullable: true)]
    private ?float $lngStart = null;

    #[ORM\Column(nullable: true)]
    private ?float $lngEnd = null;

    #[ORM\Column(nullable: true)]
    private ?float $latStart = null;

    #[ORM\Column(nullable: true)]
    private ?float $latEnd = null;

    #[ORM\ManyToOne(inversedBy: 'shippings')]
    private ?Customer $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPriceshipping(): ?float
    {
        return $this->priceshipping;
    }

    public function setPriceshipping(?float $priceshipping): self
    {
        $this->priceshipping = $priceshipping;

        return $this;
    }

    public function getLongitudeStart(): ?float
    {
        return $this->longitude_start;
    }

    public function setLongitudeStart(?float $longitude_start): self
    {
        $this->longitude_start = $longitude_start;

        return $this;
    }

    public function getLngStart(): ?float
    {
        return $this->lngStart;
    }

    public function setLngStart(?float $lngStart): self
    {
        $this->lngStart = $lngStart;

        return $this;
    }

    public function getLngEnd(): ?float
    {
        return $this->lngEnd;
    }

    public function setLngEnd(?float $lngEnd): self
    {
        $this->lngEnd = $lngEnd;

        return $this;
    }

    public function getLatStart(): ?float
    {
        return $this->latStart;
    }

    public function setLatStart(?float $latStart): self
    {
        $this->latStart = $latStart;

        return $this;
    }

    public function getLatEnd(): ?float
    {
        return $this->latEnd;
    }

    public function setLatEnd(?float $latEnd): self
    {
        $this->latEnd = $latEnd;

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
}
