<?php

namespace App\Entity;

use App\Repository\MeasureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: MeasureRepository::class)]
class Measure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    private ?int $light = null;

    #[ORM\Column]
    private ?float $distance_cm = null;

    #[ORM\Column]
    private ?bool $panel_open = null;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLight(): ?int
    {
        return $this->light;
    }

    public function setLight(int $light): static
    {
        $this->light = $light;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance_cm;
    }

    public function setDistance(float $distance): static
    {
        $this->distance_cm = $distance;

        return $this;
    }

    public function getPanelOpen(): ?bool
    {
        return $this->panel_open;
    }

    public function setPanelOpen(bool $panel_open): static
    {
        $this->panel_open = $panel_open;

        return $this;
    }
}
