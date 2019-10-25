<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $dateEntree;

    /**
     * @ORM\Column(type="DateTime")
     */
    private $duree;
   
    
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="reservation")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Room", inversedBy="reservation", cascade={"persist"})
     */
    private $room;

    public function __construct()
    {
        $this->room = new ArrayCollection();
    }
    
     
    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEntree(): ?string
    {
        return $this->dateEntree;
    }

    public function setDateEntree(string $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

  
    /**
     * @return Collection|Room[]
     */
    public function getRoom(): Collection
    {
        return $this->room;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->room->contains($room)) {
            $this->room[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->room->contains($room)) {
            $this->room->removeElement($room);
        }

        return $this;
    }
}
