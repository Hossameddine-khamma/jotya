<?php

namespace App\Entity;

use App\Repository\EnsemblesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnsemblesRepository::class)
 */
class Ensembles
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
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Produits::class, mappedBy="ensembles")
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity=Budget::class, inversedBy="ensembles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Budget;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
            $produit->addEnsemble($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeEnsemble($this);
        }

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->Budget;
    }

    public function setBudget(?Budget $Budget): self
    {
        $this->Budget = $Budget;

        return $this;
    }
}
