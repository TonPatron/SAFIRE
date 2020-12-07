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
    private $date_sortieAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ajouter;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="animes")
     */
    private $categorie;

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

    public function getDateSortieAt(): ?\DateTimeInterface
    {
        return $this->date_sortieAt;
    }

    public function setDateSortieAt(\DateTimeInterface $date_sortieAt): self
    {
        $this->date_sortieAt = $date_sortieAt;

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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
