<?php

namespace App\Entity;

use App\Repository\InstrumentsRepository;
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

    #[ORM\Column(length: 255)]
    private ?string $url_instrument = null;

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
}
