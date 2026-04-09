<?php

namespace App\Entity;

use App\Repository\ElementSceneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementSceneRepository::class)]
class ElementScene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $position_x = null;

    #[ORM\Column]
    private ?float $position_y = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Scene $scene = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Instruments $instrument = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPositionX(): ?float
    {
        return $this->position_x;
    }

    public function setPositionX(float $position_x): static
    {
        $this->position_x = $position_x;

        return $this;
    }

    public function getPositionY(): ?float
    {
        return $this->position_y;
    }

    public function setPositionY(float $position_y): static
    {
        $this->position_y = $position_y;

        return $this;
    }

    public function getScene(): ?Scene
    {
        return $this->scene;
    }

    public function setScene(?Scene $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    public function getInstrument(): ?Instruments
    {
        return $this->instrument;
    }

    public function setInstrument(?Instruments $instrument): static
    {
        $this->instrument = $instrument;

        return $this;
    }
}
