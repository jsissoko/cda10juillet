<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $info_produit = null;

    #[ORM\Column]
    private ?bool $statut_produit = null;

    #[ORM\Column(length: 25)]
    private ?string $ref_prod = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'product_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'Produit')]
    private Collection $produit;

    #[ORM\ManyToOne(inversedBy: 'Produit')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: Panier::class, inversedBy: 'produits')]
    private Collection $Produit;



    public function __construct()
    {
        // $this->produit = new ArrayCollection();
        $this->Produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
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

    public function getInfoProduit(): ?string
    {
        return $this->info_produit;
    }

    public function setInfoProduit(string $info_produit): static
    {
        $this->info_produit = $info_produit;

        return $this;
    }

    public function isStatutProduit(): ?bool
    {
        return $this->statut_produit;
    }

    public function setStatutProduit(bool $statut_produit): static
    {
        $this->statut_produit = $statut_produit;

        return $this;
    }

    public function getRefProd(): ?string
    {
        return $this->ref_prod;
    }

    public function setRefProd(string $ref_prod): static
    {
        $this->ref_prod = $ref_prod;

        return $this;
    }
    
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @return Collection<int, categorie>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Categorie $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
        }

        return $this;
    }

    public function removeProduit(Categorie $produit): static
    {
        $this->produit->removeElement($produit);

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

  

 
}
