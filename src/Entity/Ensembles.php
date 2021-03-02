<?php

namespace App\Entity;

use App\Repository\EnsemblesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=EnsemblesRepository::class)
 * @Vich\Uploadable
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
     * @Vich\UploadableField(mapping="ensembles", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Produits::class, inversedBy="ensembles" ,cascade={"persist"})
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity=Budget::class, inversedBy="ensembles")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $Budget;

    /**
     * @ORM\ManyToMany(targetEntity=Saisons::class, inversedBy="ensembles")
     */
    private $Saisons;

    /**
     * @ORM\ManyToMany(targetEntity=Styles::class, inversedBy="ensembles")
     */
    private $Styles;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="ensembles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Genre;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->Saisons = new ArrayCollection();
        $this->Styles = new ArrayCollection();
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

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
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

    /**
     * @return Collection|Saisons[]
     */
    public function getSaisons(): Collection
    {
        return $this->Saisons;
    }

    public function addSaison(Saisons $saison): self
    {
        if (!$this->Saisons->contains($saison)) {
            $this->Saisons[] = $saison;
        }

        return $this;
    }

    public function removeSaison(Saisons $saison): self
    {
        $this->Saisons->removeElement($saison);

        return $this;
    }

    /**
     * @return Collection|Styles[]
     */
    public function getStyles(): Collection
    {
        return $this->Styles;
    }

    public function addStyle(Styles $style): self
    {
        if (!$this->Styles->contains($style)) {
            $this->Styles[] = $style;
        }

        return $this;
    }

    public function removeStyle(Styles $style): self
    {
        $this->Styles->removeElement($style);

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

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }
}
