<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 * @Vich\Uploadable
 */
class Produits
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
     * @Vich\UploadableField(mapping="products", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $promotion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=Styles::class, inversedBy="produits")
     */
    private $styles;

    /**
     * @ORM\ManyToMany(targetEntity=Saisons::class, inversedBy="produits")
     */
    private $saisons;

    /**
     * @ORM\ManyToMany(targetEntity=Ensembles::class, mappedBy="produits")
     */
    private $ensembles;

    /**
     * @ORM\ManyToMany(targetEntity=Utilisateurs::class, mappedBy="favorisProduits")
     */
    private $productLovers;

    /**
     * @ORM\ManyToMany(targetEntity=Commandes::class, mappedBy="produits")
     */
    private $commandes;

    /**
     * @ORM\ManyToOne(targetEntity=Budget::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Budget;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Taille;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->styles = new ArrayCollection();
        $this->saisons = new ArrayCollection();
        $this->ensembles = new ArrayCollection();
        $this->productLovers = new ArrayCollection();
        $this->commandes = new ArrayCollection();
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


    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPromotion(): ?int
    {
        return $this->promotion;
    }

    public function setPromotion(?int $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|styles[]
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(styles $style): self
    {
        if (!$this->styles->contains($style)) {
            $this->styles[] = $style;
        }

        return $this;
    }

    public function removeStyle(styles $style): self
    {
        $this->styles->removeElement($style);

        return $this;
    }

    /**
     * @return Collection|saisons[]
     */
    public function getSaisons(): Collection
    {
        return $this->saisons;
    }

    public function addSaison(saisons $saison): self
    {
        if (!$this->saisons->contains($saison)) {
            $this->saisons[] = $saison;
        }

        return $this;
    }

    public function removeSaison(saisons $saison): self
    {
        $this->saisons->removeElement($saison);

        return $this;
    }

    /**
     * @return Collection|ensembles[]
     */
    public function getEnsembles(): Collection
    {
        return $this->ensembles;
    }

    public function addEnsemble(ensembles $ensemble): self
    {
        if (!$this->ensembles->contains($ensemble)) {
            $this->ensembles[] = $ensemble;
        }

        return $this;
    }

    public function removeEnsemble(ensembles $ensemble): self
    {
        $this->ensembles->removeElement($ensemble);

        return $this;
    }

    /**
     * @return Collection|Utilisateurs[]
     */
    public function getProductLovers(): Collection
    {
        return $this->productLovers;
    }

    public function addProductLover(Utilisateurs $productLover): self
    {
        if (!$this->productLovers->contains($productLover)) {
            $this->productLovers[] = $productLover;
            $productLover->addFavorisProduit($this);
        }

        return $this;
    }

    public function removeProductLover(Utilisateurs $productLover): self
    {
        if ($this->productLovers->removeElement($productLover)) {
            $productLover->removeFavorisProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
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

    public function getType(): ?type
    {
        return $this->Type;
    }

    public function setType(?type $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->Taille;
    }

    public function setTaille(?Taille $Taille): self
    {
        $this->Taille = $Taille;

        return $this;
    }

}
