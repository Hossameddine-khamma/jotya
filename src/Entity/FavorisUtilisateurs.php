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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tailleHaut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tailleBas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chaussures;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateurs::class, mappedBy="favorisUtilisateurs", cascade={"persist", "remove"})
     */
    private $utilisateurs;

    /**
     * @ORM\ManyToOne(targetEntity=Budget::class, inversedBy="favorisUtilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Budget;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTailleHaut(): ?string
    {
        return $this->tailleHaut;
    }

    public function setTailleHaut(?string $tailleHaut): self
    {
        $this->tailleHaut = $tailleHaut;

        return $this;
    }

    public function getTailleBas(): ?string
    {
        return $this->tailleBas;
    }

    public function setTailleBas(?string $tailleBas): self
    {
        $this->tailleBas = $tailleBas;

        return $this;
    }

    public function getChaussures(): ?string
    {
        return $this->chaussures;
    }

    public function setChaussures(?string $chaussures): self
    {
        $this->chaussures = $chaussures;

        return $this;
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
}
