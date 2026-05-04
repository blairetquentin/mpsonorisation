<?php

namespace App\Entity;

use App\Repository\InstrumentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstrumentsRepository::class)]
class Instruments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;       

    #[ORM\Column(length: 255)]
    private ?string $url_instrument = null;

    /**
     * @var Collection<int, MaterielSuggere>
     */
    #[ORM\OneToMany(targetEntity: MaterielSuggere::class, mappedBy: 'instrument')]
    private Collection $materielSuggeres;

    public function __construct()
    {
        $this->materielSuggeres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getUrlInstrument(): ?string
    {
        return $this->url_instrument;
    }

    public function setUrlInstrument(string $url_instrument): static
    {
        $this->url_instrument = $url_instrument;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, MaterielSuggere>
     */
    public function getMaterielSuggeres(): Collection
    {
        return $this->materielSuggeres;
    }

    public function addMaterielSuggere(MaterielSuggere $materielSuggere): static
    {
        if (!$this->materielSuggeres->contains($materielSuggere)) {
            $this->materielSuggeres->add($materielSuggere);
            $materielSuggere->setInstrument($this);
        }

        return $this;
    }

    public function removeMaterielSuggere(MaterielSuggere $materielSuggere): static
    {
        if ($this->materielSuggeres->removeElement($materielSuggere)) {
            // set the owning side to null (unless already changed)
            if ($materielSuggere->getInstrument() === $this) {
                $materielSuggere->setInstrument(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->libelle;
    }
}
