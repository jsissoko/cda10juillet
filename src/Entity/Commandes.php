<?php

    namespace App\Entity;



    use App\Repository\CommandesRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    
    #[ORM\Entity(repositoryClass: CommandesRepository::class)]
    class Commandes
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;
    
        #[ORM\Column(type: 'datetime')]
        private $date;
    
        #[ORM\Column(type: 'string', length: 255)]
        private $status;
    
        #[ORM\Column(type: 'float')]
        private $total;
    
        #[ORM\Column(type: 'string', length: 255)]
        private $pays;
    
        #[ORM\Column(type: 'string', length: 255)]
        private $ville;
    
        #[ORM\Column(type: 'string', length: 255)]
        private $code_postal;
    
        #[ORM\Column(type: 'string', length: 255)]
        private $nom_rue;
    
        #[ORM\Column(type: 'integer')]
        private $numero_rue;     
        
        #[ORM\Column(type: 'string' , length: 255)]
        private $informations_sup;
    
        #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
        #[ORM\JoinColumn(nullable: true)]
        private $utilisateur;
    
        #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeLigne::class, cascade: ['persist'])]
        private $commandeLignes;
    
        
        public function __construct()
        {
            $this->commandeLignes = new ArrayCollection();
            
        }

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

        public function getStatus(): ?string
        {
            return $this->status;
        }

        public function setStatus(string $status): static
        {
            $this->status = $status;

            return $this;
        }

        public function getTotal(): ?float
        {
            return $this->total;
        }

        public function setTotal(float $total): static
        {
            $this->total = $total;

            return $this;
        }

        public function getPays(): ?string
        {
            return $this->pays;
        }

        public function setPays(string $pays): static
        {
            $this->pays = $pays;

            return $this;
        }

        public function getVille(): ?string
        {
            return $this->ville;
        }

        public function setVille(string $ville): static
        {
            $this->ville = $ville;

            return $this;
        }

        public function getCodePostal(): ?string
        {
            return $this->code_postal;
        }

        public function setCodePostal(string $code_postal): static
        {
            $this->code_postal = $code_postal;

            return $this;
        }

        public function getNomRue(): ?string
        {
            return $this->nom_rue;
        }

        public function setNomRue(string $nom_rue): static
        {
            $this->nom_rue = $nom_rue;

            return $this;
        }

        public function getNumeroRue(): ?int
        {
            return $this->numero_rue;
        }

        public function setNumeroRue(int $numero_rue): static
        {
            $this->numero_rue = $numero_rue;

            return $this;
        }


        public function getInformationsSup(): ?string
        {
            return $this->informations_sup;
        }

        public function setInformationsSup(?string $informations_sup): static
        {
            $this->informations_sup = $informations_sup;

            return $this;
        }

        public function getUtilisateur(): ?utilisateur
        {
            return $this->utilisateur;
        }

        public function setUtilisateur(?utilisateur $utilisateur): static
        {
            $this->utilisateur = $utilisateur;

            return $this;
        }

        /**
         * @return Collection<int, CommandeLigne>
         */
        public function getCommandeLignes(): Collection
        {
            return $this->commandeLignes;
        }

        public function addCommandeLigne(CommandeLigne $commandeLigne): static
        {
            if (!$this->commandeLignes->contains($commandeLigne)) {
                $this->commandeLignes->add($commandeLigne);
                $commandeLigne->setCommande($this);
            }

            return $this;
        }

        public function removeCommandeLigne(CommandeLigne $commandeLigne): static
        {
            if ($this->commandeLignes->removeElement($commandeLigne)) {
                // set the owning side to null (unless already changed)
                if ($commandeLigne->getCommande() === $this) {
                    $commandeLigne->setCommande(null);
                }
            }

            return $this;
        }

        // public function getProduit(): ?Produit
        // {
        //     return $this->produit;
        // }

        // public function setProduit(?produit $produit): static
        // {
        //     $this->produit = $produit;

        //     return $this;
        // }

      
    }
