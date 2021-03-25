<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TailleRepository::class)
 */
class Taille
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
    private $valeur;

    /**
     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="Taille")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=FavorisUtilisateurs::class, mappedBy="tailleHaut")
     */
    private $tailleHautFavorisUtilisateurs;

    /**
     * @ORM\OneToMany(targetEntity=FavorisUtilisateurs::class, mappedBy="tailleBas")
     */
    private $tailleBasFavorisUtilisateurs;

    /**
     * @ORM\OneToMany(targetEntity=FavorisUtilisateurs::class, mappedBy="chaussures")
     */
    private $chassuresFavorisUtilisateurs;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->tailleHautFavorisUtilisateurs = new ArrayCollection();
        $this->tailleBasFavorisUtilisateurs = new ArrayCollection();
        $this->chassuresFavorisUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

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
            $produit->setTaille($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getTaille() === $this) {
                $produit->setTaille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FavorisUtilisateurs[]
     */
    public function getTailleHautFavorisUtilisateurs(): Collection
    {
        return $this->tailleHautFavorisUtilisateurs;
    }

    public function addTailleHautFavorisUtilisateur(FavorisUtilisateurs $tailleHautFavorisUtilisateur): self
    {
        if (!$this->tailleHautFavorisUtilisateurs->contains($tailleHautFavorisUtilisateur)) {
            $this->tailleHautFavorisUtilisateurs[] = $tailleHautFavorisUtilisateur;
            $tailleHautFavorisUtilisateur->setTailleHaut($this);
        }

        return $this;
    }

    public function removeTailleHautFavorisUtilisateur(FavorisUtilisateurs $tailleHautFavorisUtilisateur): self
    {
        if ($this->tailleHautFavorisUtilisateurs->removeElement($tailleHautFavorisUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($tailleHautFavorisUtilisateur->getTailleHaut() === $this) {
                $tailleHautFavorisUtilisateur->setTailleHaut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FavorisUtilisateurs[]
     */
    public function getTailleBasFavorisUtilisateurs(): Collection
    {
        return $this->tailleBasFavorisUtilisateurs;
    }

    public function addTailleBasFavorisUtilisateur(FavorisUtilisateurs $tailleBasFavorisUtilisateur): self
    {
        if (!$this->tailleBasFavorisUtilisateurs->contains($tailleBasFavorisUtilisateur)) {
            $this->tailleBasFavorisUtilisateurs[] = $tailleBasFavorisUtilisateur;
            $tailleBasFavorisUtilisateur->setTailleBas($this);
        }

        return $this;
    }

    public function removeTailleBasFavorisUtilisateur(FavorisUtilisateurs $tailleBasFavorisUtilisateur): self
    {
        if ($this->tailleBasFavorisUtilisateurs->removeElement($tailleBasFavorisUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($tailleBasFavorisUtilisateur->getTailleBas() === $this) {
                $tailleBasFavorisUtilisateur->setTailleBas(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FavorisUtilisateurs[]
     */
    public function getChassuresFavorisUtilisateurs(): Collection
    {
        return $this->chassuresFavorisUtilisateurs;
    }

    public function addChassuresFavorisUtilisateur(FavorisUtilisateurs $chassuresFavorisUtilisateur): self
    {
        if (!$this->chassuresFavorisUtilisateurs->contains($chassuresFavorisUtilisateur)) {
            $this->chassuresFavorisUtilisateurs[] = $chassuresFavorisUtilisateur;
            $chassuresFavorisUtilisateur->setChaussures($this);
        }

        return $this;
    }

    public function removeChassuresFavorisUtilisateur(FavorisUtilisateurs $chassuresFavorisUtilisateur): self
    {
        if ($this->chassuresFavorisUtilisateurs->removeElement($chassuresFavorisUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($chassuresFavorisUtilisateur->getChaussures() === $this) {
                $chassuresFavorisUtilisateur->setChaussures(null);
            }
        }

        return $this;
    }
}
