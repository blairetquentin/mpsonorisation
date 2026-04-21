<?php

namespace App\Entity;

use App\Repository\ConfigBatterieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigBatterieRepository::class)]
class ConfigBatterie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbToms = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbCymbales = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbCaisseClaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbGrosseCaisse = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbCharleston = null;

    #[ORM\ManyToOne(inversedBy: 'configBatteries')]
    private ?ElementScene $elementScene = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbToms(): ?int
    {
        return $this->nbToms;
    }

    public function setNbToms(?int $nbToms): static
    {
        $this->nbToms = $nbToms;
        return $this;
    }

    public function getNbCymbales(): ?int
    {
        return $this->nbCymbales;
    }

    public function setNbCymbales(?int $nbCymbales): static
    {
        $this->nbCymbales = $nbCymbales;
        return $this;
    }

    public function getNbCaisseClaire(): ?int
    {
        return $this->nbCaisseClaire;
    }

    public function setNbCaisseClaire(?int $nbCaisseClaire): static
    {
        $this->nbCaisseClaire = $nbCaisseClaire;
        return $this;
    }

    public function getNbGrosseCaisse(): ?int
    {
        return $this->nbGrosseCaisse;
    }

    public function setNbGrosseCaisse(?int $nbGrosseCaisse): static
    {
        $this->nbGrosseCaisse = $nbGrosseCaisse;
        return $this;
    }

    public function getNbCharleston(): ?int
    {
        return $this->nbCharleston;
    }

    public function setNbCharleston(?int $nbCharleston): static
    {
        $this->nbCharleston = $nbCharleston;
        return $this;
    }

    public function getElementScene(): ?ElementScene
    {
        return $this->elementScene;
    }

    public function setElementScene(?ElementScene $elementScene): static
    {
        $this->elementScene = $elementScene;
        return $this;
    }
}