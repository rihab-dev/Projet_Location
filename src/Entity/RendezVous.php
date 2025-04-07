<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $etudiant = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $proprietaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $heure = null;

    #[ORM\Column(length: 255)]
    private string $statut = self::STATUT_EN_ATTENTE;

    // Constantes pour les statuts possibles
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_CONFIRME = 'confirme';

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        if (!in_array($statut, self::getStatutsDisponibles())) {
            throw new \InvalidArgumentException("Statut invalide");
        }

        $this->statut = $statut;
        return $this;
    }

    public static function getStatutsDisponibles(): array
    {
        return [
            self::STATUT_EN_ATTENTE,
            self::STATUT_CONFIRME,
        ];
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
