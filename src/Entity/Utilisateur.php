<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Panier;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_rue = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_rue = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(type: 'string', length: 180)]
    private ?string $verificationCode = null;

    #[ORM\Column(type: 'boolean')]
    private $isActive = false;

    #[ORM\OneToMany(mappedBy: 'Utilisateur', targetEntity: Produit::class)]
    private Collection $produit;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'utilisateurs')]
    private ?self $Utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'Utilisateur', targetEntity: self::class)]
    private Collection $utilisateurs;


    // #[ORM\OneToOne(targetEntity: Panier::class, mappedBy: 'utilisateur', cascade: ['persist', 'remove'])]
    // private ?Panier $panier = null;

    #[ORM\OneToMany(mappedBy: 'expediteur', targetEntity: Messages::class, orphanRemoval: true)]
    private Collection $sent;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: Messages::class, orphanRemoval: true)]
    private Collection $recu;

     // Attributs pour la relation Employé (ManyToOne)
     #[ORM\ManyToOne(targetEntity: "Utilisateur", inversedBy: "clients")]
     #[ORM\JoinColumn(name: "employe_id", referencedColumnName: "id", nullable: true)]
     private ?Utilisateur $employe = null;
 
     // Attributs pour la collection de Clients (OneToMany)
     #[ORM\OneToMany(mappedBy: "employe", targetEntity: "Utilisateur")]
     private Collection $clients;

     #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commandes::class)]
     private Collection $commandes;


  
    public function __construct()
    {
        $this->produit = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->recu = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }
 
    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self // static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getNomRue(): ?string
    {
        return $this->nom_rue;
    }

    public function setNomRue(string $nom_rue): static
    {
        $this->nom_rue = $nom_rue;

        return $this;
    }

    public function getNumeroRue(): ?string
    {
        return $this->numero_rue;
    }

    public function setNumeroRue(string $numero_rue): static
    {
        $this->numero_rue = $numero_rue;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }
   
    // Getter pour le code de vérification
    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }

    // Setter pour le code de vérification
    public function setVerificationCode(?string $verificationCode): self
    {
        $this->verificationCode = $verificationCode;
        return $this;
    }

    // Getter pour isActive
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    // Setter pour isActive
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    // public function getPanier(): ?Panier
    // {
    //     return $this->panier;
    // }

    // public function setPanier(?Panier $panier): self
    // {
    //     $this->panier = $panier;

    //     // configuration de la relation inverse si nécessaire
    //     if ($panier !== null && $panier->getUtilisateur() !== $this) {
    //         $panier->setUtilisateur($this);
    //     }
    //     return $this;
    // }

 
    public function getEmploye(): ?Utilisateur
    {
        return $this->employe;
    }

    public function setEmploye(?Utilisateur $employe): self
    {
        $this->employe = $employe;
        return $this;
    }

    // Getters et Setters pour clients
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Utilisateur $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setEmploye($this);
        }
        return $this;
    }

    public function removeClient(Utilisateur $client): self
    {
        if ($this->clients->removeElement($client)) {
            if ($client->getEmploye() === $this) {
                $client->setEmploye(null);
            }
        }
        return $this;
    }

    


    /**
     * @return Collection<int, produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(produit $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->setUtilisateur($this);
        }

        return $this;
    }

    public function removeProduit(produit $produit): static
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUtilisateur() === $this) {
                $produit->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?self
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?self $Utilisateur): static
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(self $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setUtilisateur($this);
        }

        return $this;
    }

    public function removeUtilisateur(self $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getUtilisateur() === $this) {
                $utilisateur->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Messages $sent): static
    {
        if (!$this->sent->contains($sent)) {
            $this->sent->add($sent);
            $sent->setExpediteur($this);
        }

        return $this;
    }

    public function removeSent(Messages $sent): static
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getExpediteur() === $this) {
                $sent->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getRecu(): Collection
    {
        return $this->recu;
    }

    public function addRecu(Messages $recu): static
    {
        if (!$this->recu->contains($recu)) {
            $this->recu->add($recu);
            $recu->setDestinataire($this);
        }

        return $this;
    }

    public function removeRecu(Messages $recu): static
    {
        if ($this->recu->removeElement($recu)) {
            // set the owning side to null (unless already changed)
            if ($recu->getDestinataire() === $this) {
                $recu->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }

        return $this;
    }
    
}

// if ($this->isGranted('ROLE_ADMIN')) {

//     // Code pour les utilisateurs avec le rôle ROLE_ADMIN
// }

// if ($this->isGranted('ROLE_USER')) {
//     // Code pour les utilisateurs avec le rôle ROLE_USER
// }
