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

    #[ORM\OneToOne(inversedBy: 'configBatterie')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ElementScene $elementScene = null;

    #[ORM\ManyToOne]
    private ?MaterielSuggere $microTom = null;

    #[ORM\ManyToOne]
    private ?MaterielSuggere $microCymbale = null;

    #[ORM\ManyToOne]
    private ?MaterielSuggere $microGrosseCaisse = null;

    #[ORM\ManyToOne]
    private ?MaterielSuggere $microCaisseClaire = null;

    #[ORM\ManyToOne]
    private ?MaterielSuggere $microCharleston = null;

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

    
    public function __toString(): string
    {
        return 'Batterie - ' . $this->elementScene->getScene()->getNomArtiste();
    }

    public function getMicroTom(): ?MaterielSuggere
    {
        return $this->microTom;
    }

    public function setMicroTom(?MaterielSuggere $microTom): static
    {
        $this->microTom = $microTom;

        return $this;
    }

    public function getMicroCymbale(): ?MaterielSuggere
    {
        return $this->microCymbale;
    }

    public function setMicroCymbale(?MaterielSuggere $microCymbale): static
    {
        $this->microCymbale = $microCymbale;

        return $this;
    }

    public function getMicroGrosseCaisse(): ?MaterielSuggere
    {
        return $this->microGrosseCaisse;
    }

    public function setMicroGrosseCaisse(?MaterielSuggere $microGrosseCaisse): static
    {
        $this->microGrosseCaisse = $microGrosseCaisse;

        return $this;
    }

    public function getMicroCaisseClaire(): ?MaterielSuggere
    {
        return $this->microCaisseClaire;
    }

    public function setMicroCaisseClaire(?MaterielSuggere $microCaisseClaire): static
    {
        $this->microCaisseClaire = $microCaisseClaire;

        return $this;
    }

    public function getMicroCharleston(): ?MaterielSuggere
    {
        return $this->microCharleston;
    }

    public function setMicroCharleston(?MaterielSuggere $microCharleston): static
    {
        $this->microCharleston = $microCharleston;

        return $this;
    }
}