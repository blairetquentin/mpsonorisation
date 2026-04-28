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

    /**
     * @var Collection<int, ConfigBatterie>
     */
    #[ORM\OneToMany(targetEntity: ConfigBatterie::class, mappedBy: 'elementScene')]
    private Collection $configBatteries;

    public function __construct()
    {
        $this->configBatteries = new ArrayCollection();
    }

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
    /**
     * @return Collection<int, ConfigBatterie>
     */
    public function getConfigBatteries(): Collection
    {
        return $this->configBatteries;
    }

    public function addConfigBattery(ConfigBatterie $configBattery): static
    {
        if (!$this->configBatteries->contains($configBattery)) {
            $this->configBatteries->add($configBattery);
            $configBattery->setElementScene($this);
        }

        return $this;
    }

    public function removeConfigBattery(ConfigBatterie $configBattery): static
    {
        if ($this->configBatteries->removeElement($configBattery)) {
            if ($configBattery->getElementScene() === $this) {
                $configBattery->setElementScene(null);
            }
        }

        return $this;
    }
}

