<?php

namespace App\Entity;

use App\Repository\FavorisUtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavorisUtilisateursRepository::class)
 */
class FavorisUtilisateurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateurs::class, inversedBy="favorisUtilisateurs", cascade={"persist", "remove"})
     */
    private $utilisateurs;

    /**
     * @ORM\ManyToOne(targetEntity=Budget::class, inversedBy="favorisUtilisateurs")
     */
    private $Budget;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class, inversedBy="tailleHautFavorisUtilisateurs")
     */
    private $tailleHaut;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class, inversedBy="tailleBasFavorisUtilisateurs")
     */
    private $tailleBas;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class, inversedBy="chassuresFavorisUtilisateurs")
     */
    private $chaussures;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="Utilisateurs")
     */
    private $Genre;

    /**
     * @ORM\ManyToOne(targetEntity=Styles::class, inversedBy="favorisUtilisateurs")
     */
    private $Style;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurs(): ?Utilisateurs
    {
        return $this->utilisateurs;
    }

    public function setUtilisateurs(?Utilisateurs $utilisateurs): self
    {
        // unset the owning side of the relation if necessary
        if ($utilisateurs === null && $this->utilisateurs !== null) {
            $this->utilisateurs->setFavorisUtilisateurs(null);
        }

        // set the owning side of the relation if necessary
        if ($utilisateurs !== null && $utilisateurs->getFavorisUtilisateurs() !== $this) {
            $utilisateurs->setFavorisUtilisateurs($this);
        }

        $this->utilisateurs = $utilisateurs;

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

    public function getTailleHaut(): ?Taille
    {
        return $this->tailleHaut;
    }

    public function setTailleHaut(?Taille $tailleHaut): self
    {
        $this->tailleHaut = $tailleHaut;

        return $this;
    }

    public function getTailleBas(): ?Taille
    {
        return $this->tailleBas;
    }

    public function setTailleBas(?Taille $tailleBas): self
    {
        $this->tailleBas = $tailleBas;

        return $this;
    }

    public function getChaussures(): ?Taille
    {
        return $this->chaussures;
    }

    public function setChaussures(?Taille $chaussures): self
    {
        $this->chaussures = $chaussures;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->Genre;
    }

    public function setGenre(?Genre $Genre): self
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getStyle(): ?Styles
    {
        return $this->Style;
    }

    public function setStyle(?Styles $Style): self
    {
        $this->Style = $Style;

        return $this;
    }

}
