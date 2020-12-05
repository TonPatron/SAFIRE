<?php

namespace App\Entity;

use App\Repository\SeriesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeriesRepository::class)
 */
class Series
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
    private $imageSerie;

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

    public function getImageSerie(): ?string
    {
        return $this->imageSerie;
    }

    public function setImageSerie(string $imageSerie): self
    {
        $this->imageSerie = $imageSerie;

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
}
