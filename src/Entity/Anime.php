<?php

namespace App\Entity;

use App\Repository\AnimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnimeRepository::class)
 */
class Anime
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageAnime;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDeSortieAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ajouter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getImageAnime(): ?string
    {
        return $this->imageAnime;
    }

    public function setImageAnime(string $imageAnime): self
    {
        $this->imageAnime = $imageAnime;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDeSortieAt(): ?\DateTimeInterface
    {
        return $this->dateDeSortieAt;
    }

    public function setDateDeSortieAt(\DateTimeInterface $dateDeSortieAt): self
    {
        $this->dateDeSortieAt = $dateDeSortieAt;

        return $this;
    }

    public function getAjouter(): ?bool
    {
        return $this->ajouter;
    }

    public function setAjouter(bool $ajouter): self
    {
        $this->ajouter = $ajouter;

        return $this;
    }
}
