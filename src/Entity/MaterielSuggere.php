<?php

namespace App\Entity;

use App\Repository\MaterielSuggereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielSuggereRepository::class)]
class MaterielSuggere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $materiel = null;

    #[ORM\ManyToOne(inversedBy: 'materielSuggeres')]
    private ?Instruments $instrument = null;

    /**
     * @var Collection<int, ElementScene>
     */
    #[ORM\OneToMany(targetEntity: ElementScene::class, mappedBy: 'materielSuggere')]
    private Collection $elementScenes;

    #[ORM\ManyToOne(inversedBy: 'materielSuggeres')]
    private ?ConfigBatterie $configBatterie = null;

    public function __construct()
    {
        $this->elementScenes = new ArrayCollection();
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


    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;

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

    /**
     * @return Collection<int, ElementScene>
     */
    public function getElementScenes(): Collection
    {
        return $this->elementScenes;
    }

    public function addElementScene(ElementScene $elementScene): static
    {
        if (!$this->elementScenes->contains($elementScene)) {
            $this->elementScenes->add($elementScene);
            $elementScene->setMaterielSuggere($this);
        }

        return $this;
    }

    public function removeElementScene(ElementScene $elementScene): static
    {
        if ($this->elementScenes->removeElement($elementScene)) {
            if ($elementScene->getMaterielSuggere() === $this) {
                $elementScene->setMaterielSuggere(null);
            }
        }

        return $this;
    }

}
