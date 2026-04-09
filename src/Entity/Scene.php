<?php

namespace App\Entity;

use App\Repository\SceneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceneRepository::class)]
class Scene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_evenement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_evenement = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_artiste = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getNomEvenement(): ?string
    {
        return $this->nom_evenement;
    }

    public function setNomEvenement(string $nom_evenement): static
    {
        $this->nom_evenement = $nom_evenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTime
    {
        return $this->date_evenement;
    }

    public function setDateEvenement(\DateTime $date_evenement): static
    {
        $this->date_evenement = $date_evenement;

        return $this;
    }

    public function getNomArtiste(): ?string
    {
        return $this->nom_artiste;
    }

    public function setNomArtiste(string $nom_artiste): static
    {
        $this->nom_artiste = $nom_artiste;

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
}
