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

    

    #[ORM\ManyToOne(inversedBy: 'Produit')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'produits')]
    private Collection $categories;


    
    public function __toString()
    {
        return $this->nom;
    }


    public function __construct()
    {
        // $this->produit = new ArrayCollection();
        // $this->Produit = new ArrayCollection();
        // $this->categoriesCollection = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

   

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduit($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduit($this);
        }

        return $this;
    }

  
  
 
}
