<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\room", inversedBy="commentaires")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\client", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

 

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->Commentaire = $commentaire;

        return $this;
    }

    public function getRoom(): ?room
    {
        return $this->room;
    }

    public function setRoom(?room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getAuteur(): ?client
    {
        return $this->auteur;
    }

    public function setAuteur(?client $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }


}
