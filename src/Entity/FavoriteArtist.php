<?php

namespace App\Entity;

use App\Repository\FavoriteArtistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteArtistRepository::class)]
class FavoriteArtist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $artistId = null;

    #[ORM\Column(type: 'string')]
    private ?string $name = null;

    #[ORM\Column(type: 'string')]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtistId(): ?string
    {
        return $this->artistId;
    }

    public function setArtistId(string $artistId): static
    {
        $this->artistId = $artistId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
