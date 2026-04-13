<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable:true)]
    private ?\DateTime $date_location = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $adresse_location = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity:PanierMateriel::class, mappedBy:'panier')]
    private Collection $panierMateriels;

    public function __construct()
    {
        $this->panierMateriels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLocation(): ?\DateTime
    {
        return $this->date_location;
    }

    public function setDateLocation(\DateTime $date_location): static
    {
        $this->date_location = $date_location;

        return $this;
    }

    public function getAdresseLocation(): ?string
    {
        return $this->adresse_location;
    }

    public function setAdresseLocation(string $adresse_location): static
    {
        $this->adresse_location = $adresse_location;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function getPanierMateriel(): Collection{
        return $this->panierMateriels;
    }

}
