<?php

namespace App\Entity;

use App\Repository\GpsDeviceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GpsDeviceRepository::class)]
class GpsDevice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sim_number = null;
    #[ORM\Column(length: 255)]
    private ?string $emei = null;
    #[ORM\Column(length: 255)]
    private ?string $license = null;
    #[ORM\Column(length: 255)]
    private ?string $operator = null;
    #[ORM\Column(length: 255)]
    private ?string $contactadmin = "0";
    #[ORM\Column(length: 255)]
    private ?string $lastLongitude = "0";

    #[ORM\Column(length: 255)]
    private ?string $lastLatitude = "0";

    #[ORM\Column(length: 255)]
    private ?string $lastSpeed = "0";

    #[ORM\Column(length: 255)]
    private ?string $lastAltitude = "0";

    #[ORM\Column(length: 255)]
    private ?string $lastStatus = "0";

    #[ORM\Column(length: 255)]
    private ?string $lastTrackTime = "";

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContactadmin(): ?string
    {
        return $this->contactadmin;
    }

    /**
     * @param string|null $contactadmin
     */
    public function setContactadmin(?string $contactadmin): void
    {
        $this->contactadmin = $contactadmin;
    }

    /**
     * @return string|null
     */
    public function getLicense(): ?string
    {
        return $this->license;
    }

    /**
     * @param string|null $license
     */
    public function setLicense(?string $license): void
    {
        $this->license = $license;
    }

    /**
     * @return string|null
     */
    public function getOperator(): ?string
    {
        return $this->operator;
    }

    /**
     * @param string|null $operator
     */
    public function setOperator(?string $operator): void
    {
        $this->operator = $operator;
    }

    public function getSimNumber(): ?string
    {
        return $this->sim_number;
    }

    public function setSimNumber(string $sim_number): self
    {
        $this->sim_number = $sim_number;

        return $this;
    }

    public function getEmei(): ?string
    {
        return $this->emei;
    }

    public function setEmei(string $emei): self
    {
        $this->emei = $emei;

        return $this;
    }

    public function getLastLongitude(): ?string
    {
        return $this->lastLongitude;
    }

    public function setLastLongitude(string $lastLongitude): self
    {
        $this->lastLongitude = $lastLongitude;

        return $this;
    }

    public function getLastLatitude(): ?string
    {
        return $this->lastLatitude;
    }

    public function setLastLatitude(string $lastLatitude): self
    {
        $this->lastLatitude = $lastLatitude;

        return $this;
    }

    public function getLastSpeed(): ?string
    {
        return $this->lastSpeed;
    }

    public function setLastSpeed(string $lastSpeed): self
    {
        $this->lastSpeed = $lastSpeed;

        return $this;
    }

    public function getLastAltitude(): ?string
    {
        return $this->lastAltitude;
    }

    public function setLastAltitude(string $lastAltitude): self
    {
        $this->lastAltitude = $lastAltitude;

        return $this;
    }

    public function getLastStatus(): ?string
    {
        return $this->lastStatus;
    }

    public function setLastStatus(string $lastStatus): self
    {
        $this->lastStatus = $lastStatus;

        return $this;
    }

    public function getLastTrackTime(): ?string
    {
        return $this->lastTrackTime;
    }

    public function setLastTrackTime(string $lastTrackTime): self
    {
        $this->lastTrackTime = $lastTrackTime;

        return $this;
    }
}
