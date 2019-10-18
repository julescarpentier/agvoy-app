<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndisponibiliteRepository")
 */
class Indisponibilite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $indisponible;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="disponibilite")
     */
    private $room;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndisponible(): ?bool
    {
        return $this->indisponible;
    }

    public function setIndisponible(bool $indisponible): self
    {
        $this->indisponible = $indisponible;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }
}
