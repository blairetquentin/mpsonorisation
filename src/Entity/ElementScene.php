<?php

namespace App\Entity;

use App\Repository\ElementSceneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementSceneRepository::class)]
class ElementScene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable:true)]
    private ?int $quantite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Scene $scene = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Instruments $instrument = null;

    #[ORM\Column(length: 100)]
    private ?string $nom_musicien = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Instruments $equipement = null;
  
    #[ORM\OneToOne(targetEntity: ConfigBatterie::class, mappedBy: 'elementScene')]
    private ?ConfigBatterie $configBatterie = null;

    #[ORM\ManyToOne(inversedBy: 'elementScenes')]
    private ?MaterielSuggere $materielSuggere = null;

    #[ORM\ManyToOne(inversedBy: 'elementScenes')]
    private ?Instruments $micro = null;

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
    
    public function getNomMusicien(): ?string
    {
        return $this->nom_musicien;
    }

    public function setNomMusicien(string $nom_musicien): static
    {
        $this->nom_musicien = $nom_musicien;

        return $this;
    }
        public function getEquipement(): ?Instruments
    {
        return $this->equipement;
    }

    public function setEquipement(?Instruments $equipement): static
    {
    $this->equipement = $equipement;
    return $this;
    }

    public function getConfigBatterie(): ?ConfigBatterie
    {
        return $this->configBatterie;
    }

    public function setConfigBatterie(?ConfigBatterie $configBatterie): static
    {
        $this->configBatterie = $configBatterie;
        return $this;
    }

    public function getMaterielSuggere(): ?MaterielSuggere
    {
        return $this->materielSuggere;
    }

    public function setMaterielSuggere(?MaterielSuggere $materielSuggere): static
    {
        $this->materielSuggere = $materielSuggere;

        return $this;
    }

    public function getMicro(): ?Instruments
    {
        return $this->micro;
    }

    public function setMicro(?Instruments $micro): static
    {
        $this->micro = $micro;

        return $this;
    }
}

