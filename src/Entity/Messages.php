<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessagesRepository::class)
 */
class Messages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private $status = false;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="messagesEnvoyer")
     */
    private $expiditeur;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateurs::class, inversedBy="messagesRecu")
     */
    private $destinataire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getExpiditeur(): ?utilisateurs
    {
        return $this->expiditeur;
    }

    public function setExpiditeur(?utilisateurs $expiditeur): self
    {
        $this->expiditeur = $expiditeur;

        return $this;
    }

    public function getDestinataire(): ?utilisateurs
    {
        return $this->destinataire;
    }

    public function setDestinataire(?utilisateurs $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

}
