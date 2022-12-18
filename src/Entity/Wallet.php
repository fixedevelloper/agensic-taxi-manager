<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use DateTimeTrait;
    #[ORM\Column]
    private ?float $amount = null;
    #[ORM\Column]
    private ?float $total = 0.0;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $beneficiare = null;

    #[ORM\Column(length: 255)]
    private ?string $walletnumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): void
    {
        $this->total = $total;
    }

    public function getBeneficiare(): ?User
    {
        return $this->beneficiare;
    }

    public function setBeneficiare(?User $beneficiare): self
    {
        $this->beneficiare = $beneficiare;

        return $this;
    }

    public function getWalletnumber(): ?string
    {
        return $this->walletnumber;
    }

    public function setWalletnumber(string $walletnumber): self
    {
        $this->walletnumber = $walletnumber;

        return $this;
    }
}
