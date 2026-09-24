<?php

namespace App\Entity;

use App\Repository\MeasureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: MeasureRepository::class)]
class Measure
{
    // Identifiant généré par Doctrine pour chaque mesure.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Date de réception de la mesure, utilisée pour le tri historique.
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    // Valeur brute fournie par le capteur de luminosité.
    #[ORM\Column]
    private ?int $light = null;

    // Distance mesurée en centimètres.
    #[ORM\Column]
    private ?float $distance_cm = null;

    // État du panneau au moment de la mesure.
    #[ORM\Column]
    private ?bool $panel_open = null;


    public function __construct()
    {
        // Date automatiquement fixée à la création de l'entité.
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
