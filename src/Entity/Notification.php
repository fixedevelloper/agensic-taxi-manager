<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    use DateTimeTrait;
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(nullable: true)]
    private ?int $userid = null;
    #[ORM\Column(nullable: true)]
    private ?int $propretaire = null;

    #[ORM\Column(nullable: true)]
    private ?bool $alldriver = false;

    #[ORM\Column(nullable: true)]
    private ?bool $allcustomer = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $sendDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPropretaire(): ?int
    {
        return $this->propretaire;
    }

    /**
     * @param int|null $propretaire
     */
    public function setPropretaire(?int $propretaire): void
    {
        $this->propretaire = $propretaire;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(?int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function isAlldriver(): ?bool
    {
        return $this->alldriver;
    }

    public function setAlldriver(?bool $alldriver): self
    {
        $this->alldriver = $alldriver;

        return $this;
    }

    public function isAllcustomer(): ?bool
    {
        return $this->allcustomer;
    }

    public function setAllcustomer(?bool $allcustomer): self
    {
        $this->allcustomer = $allcustomer;

        return $this;
    }


    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(?\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }
}
