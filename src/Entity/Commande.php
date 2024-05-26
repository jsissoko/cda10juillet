<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_commande = null;

    #[ORM\Column]
    private ?float $total_commande = null;

    #[ORM\Column]
    private ?bool $paiement_valide = null;

    #[ORM\Column(length: 255)]
    private ?string $etat_commande = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeImmutable
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeImmutable $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getTotalCommande(): ?float
    {
        return $this->total_commande;
    }

    public function setTotalCommande(float $total_commande): static
    {
        $this->total_commande = $total_commande;

        return $this;
    }

    public function isPaiementValide(): ?bool
    {
        return $this->paiement_valide;
    }

    public function setPaiementValide(bool $paiement_valide): static
    {
        $this->paiement_valide = $paiement_valide;

        return $this;
    }

    public function getEtatCommande(): ?string
    {
        return $this->etat_commande;
    }

    public function setEtatCommande(string $etat_commande): static
    {
        $this->etat_commande = $etat_commande;

        return $this;
    }

   
}
