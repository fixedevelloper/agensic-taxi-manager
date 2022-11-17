<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
class Configuration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $tarifkm = null;

    #[ORM\Column]
    private ?float $tarifheure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarifkm(): ?float
    {
        return $this->tarifkm;
    }

    public function setTarifkm(float $tarifkm): self
    {
        $this->tarifkm = $tarifkm;

        return $this;
    }

    public function getTarifheure(): ?float
    {
        return $this->tarifheure;
    }

    public function setTarifheure(float $tarifheure): self
    {
        $this->tarifheure = $tarifheure;

        return $this;
    }
}
