<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisateursRepository::class)
 *  @UniqueEntity(fields={"email"}, message="l'email que vous avez indiqué est deja utilisé")
 */
class Utilisateurs implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Regex(pattern = "/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/", match = true, message = "veuillez saisir une adresse mail correcte")
     * @Assert\NotBlank(message="Veuillez saisir une valeur")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/",
     *                   match = true,
     *                  message = "Un mot de passe valide aura: de 8 à 15 caractères, au moins une lettre minuscule, au moins une lettre majuscule, au moins un chiffre et au moins un caractère spécial")
     * @Assert\NotBlank(message="Veuillez saisir une valeur")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath = "password", message="veuillez saisir le même mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDeNaissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\OneToOne(targetEntity=FavorisUtilisateurs::class, mappedBy="utilisateurs", cascade={"persist", "remove"})
     */
    private $favorisUtilisateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Produits::class, inversedBy="productLovers")
     */
    private $favorisProduits;

    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="utilisateurs")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="expiditeur")
     */
    private $messagesEnvoyer;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="destinataire")
     */
    private $messagesRecu;


    public function __construct()
    {
        $this->favorisProduits = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->messagesEnvoyer = new ArrayCollection();
        $this->messagesRecu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTimeInterface $dateDeNaissance): self
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFavorisUtilisateurs(): ?favorisUtilisateurs
    {
        return $this->favorisUtilisateurs;
    }

    public function setFavorisUtilisateurs(?FavorisUtilisateurs $FavorisUtilisateurs): self
    {
        $this->FavorisUtilisateurs = $FavorisUtilisateurs;

        return $this;
    }
    
    /**
     * @return Collection|produits[]
     */
    public function getFavorisProduits(): Collection
    {
        return $this->favorisProduits;
    }

    public function addFavorisProduit(produits $favorisProduit): self
    {
        if (!$this->favorisProduits->contains($favorisProduit)) {
            $this->favorisProduits[] = $favorisProduit;
        }

        return $this;
    }

    public function removeFavorisProduit(produits $favorisProduit): self
    {
        $this->favorisProduits->removeElement($favorisProduit);

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
            $commande->setUtilisateurs($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateurs() === $this) {
                $commande->setUtilisateurs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessagesEnvoyer(): Collection
    {
        return $this->messagesEnvoyer;
    }

    public function addMessagesEnvoyer(Messages $messagesEnvoyer): self
    {
        if (!$this->messagesEnvoyer->contains($messagesEnvoyer)) {
            $this->messagesEnvoyer[] = $messagesEnvoyer;
            $messagesEnvoyer->setExpiditeur($this);
        }

        return $this;
    }

    public function removeMessagesEnvoyer(Messages $messagesEnvoyer): self
    {
        if ($this->messagesEnvoyer->removeElement($messagesEnvoyer)) {
            // set the owning side to null (unless already changed)
            if ($messagesEnvoyer->getExpiditeur() === $this) {
                $messagesEnvoyer->setExpiditeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessagesRecu(): Collection
    {
        return $this->messagesRecu;
    }

    public function addMessagesRecu(Messages $messagesRecu): self
    {
        if (!$this->messagesRecu->contains($messagesRecu)) {
            $this->messagesRecu[] = $messagesRecu;
            $messagesRecu->setDestinataire($this);
        }

        return $this;
    }

    public function removeMessagesRecu(Messages $messagesRecu): self
    {
        if ($this->messagesRecu->removeElement($messagesRecu)) {
            // set the owning side to null (unless already changed)
            if ($messagesRecu->getDestinataire() === $this) {
                $messagesRecu->setDestinataire(null);
            }
        }

        return $this;
    }
}
