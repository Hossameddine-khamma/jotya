<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BudgetRepository::class)
 */
class Budget
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="Budget")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=Ensembles::class, mappedBy="Budget")
     */
    private $ensembles;

    /**
     * @ORM\OneToMany(targetEntity=FavorisUtilisateurs::class, mappedBy="Budget")
     */
    private $favorisUtilisateurs;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->ensembles = new ArrayCollection();
        $this->favorisUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $produit->setBudget($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getBudget() === $this) {
                $produit->setBudget(null);
            }
        }

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
            $ensemble->setBudget($this);
        }

        return $this;
    }

    public function removeEnsemble(Ensembles $ensemble): self
    {
        if ($this->ensembles->removeElement($ensemble)) {
            // set the owning side to null (unless already changed)
            if ($ensemble->getBudget() === $this) {
                $ensemble->setBudget(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FavorisUtilisateurs[]
     */
    public function getFavorisUtilisateurs(): Collection
    {
        return $this->favorisUtilisateurs;
    }

    public function addFavorisUtilisateur(FavorisUtilisateurs $favorisUtilisateur): self
    {
        if (!$this->favorisUtilisateurs->contains($favorisUtilisateur)) {
            $this->favorisUtilisateurs[] = $favorisUtilisateur;
            $favorisUtilisateur->setBudget($this);
        }

        return $this;
    }

    public function removeFavorisUtilisateur(FavorisUtilisateurs $favorisUtilisateur): self
    {
        if ($this->favorisUtilisateurs->removeElement($favorisUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($favorisUtilisateur->getBudget() === $this) {
                $favorisUtilisateur->setBudget(null);
            }
        }

        return $this;
    }
}
