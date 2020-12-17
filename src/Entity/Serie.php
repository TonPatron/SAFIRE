<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SerieRepository::class)
 */
class Serie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $imageSerie;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime")
     */
    private $dateDeSortieAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ajouter;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="series")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;



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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }


}
