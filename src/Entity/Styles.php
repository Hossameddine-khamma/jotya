<?php

namespace App\Entity;

use App\Repository\StylesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StylesRepository::class)
 */
class Styles
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
     * @ORM\ManyToMany(targetEntity=Produits::class, mappedBy="styles")
     */
    private $produits;

    /**
     * @ORM\ManyToMany(targetEntity=Ensembles::class, mappedBy="Styles")
     */
    private $ensembles;

    /**
     * @ORM\OneToMany(targetEntity=FavorisUtilisateurs::class, mappedBy="Style")
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
            $produit->addStyle($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeStyle($this);
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
            $ensemble->addStyle($this);
        }

        return $this;
    }

    public function removeEnsemble(Ensembles $ensemble): self
    {
        if ($this->ensembles->removeElement($ensemble)) {
            $ensemble->removeStyle($this);
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
            $favorisUtilisateur->setStyle($this);
        }

        return $this;
    }

    public function removeFavorisUtilisateur(FavorisUtilisateurs $favorisUtilisateur): self
    {
        if ($this->favorisUtilisateurs->removeElement($favorisUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($favorisUtilisateur->getStyle() === $this) {
                $favorisUtilisateur->setStyle(null);
            }
        }

        return $this;
    }

}
