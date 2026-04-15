<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url_materiel = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column]
    private ?int $stock_dispo = null;

    #[ORM\Column]
    private ?int $stock_total = null;

    #[ORM\ManyToOne(inversedBy:'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SousCategorie $sous_categorie = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

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

    public function getUrlMateriel(): ?string
    {
        return $this->url_materiel;
    }

    public function setUrlMateriel(?string $url_materiel): static
    {
        $this->url_materiel = $url_materiel;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStockDispo(): ?int
    {
        return $this->stock_dispo;
    }

    public function setStockDispo(int $stock_dispo): static
    {
        $this->stock_dispo = $stock_dispo;

        return $this;
    }

    public function getStockTotal(): ?int
    {
        return $this->stock_total;
    }

    public function setStockTotal(int $stock_total): static
    {
        $this->stock_total = $stock_total;

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sous_categorie;
    }

    public function setSousCategorie(?SousCategorie $sous_categorie): static
    {
        $this->sous_categorie = $sous_categorie;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
}
