<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Ensembles::class, mappedBy="Genre")
     */
    private $ensembles;

    /**
     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="Genre")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=FavorisUtilisateurs::class, mappedBy="Genre")
     */
    private $Utilisateurs;

    public function __construct()
    {
        $this->ensembles = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->favorisUtilisateurs = new ArrayCollection();
        $this->Utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Ensembles[]
     */
    public function getEnsembles(): Collection
    {
        return $this->ensembles;
    }

    public function addEnsemble(Ensembles $ensemble): self
    {
        if (!$this->ensembles->contains($ensemble)) {
            $this->ensembles[] = $ensemble;
            $ensemble->setGenre($this);
        }

        return $this;
    }

    public function removeEnsemble(Ensembles $ensemble): self
    {
        if ($this->ensembles->removeElement($ensemble)) {
            // set the owning side to null (unless already changed)
            if ($ensemble->getGenre() === $this) {
                $ensemble->setGenre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setGenre($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getGenre() === $this) {
                $produit->setGenre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FavorisUtilisateurs[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->Utilisateurs;
    }

    public function addUtilisateur(FavorisUtilisateurs $utilisateur): self
    {
        if (!$this->Utilisateurs->contains($utilisateur)) {
            $this->Utilisateurs[] = $utilisateur;
            $utilisateur->setGenre($this);
        }

        return $this;
    }

    public function removeUtilisateur(FavorisUtilisateurs $utilisateur): self
    {
        if ($this->Utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getGenre() === $this) {
                $utilisateur->setGenre(null);
            }
        }

        return $this;
    }
}
