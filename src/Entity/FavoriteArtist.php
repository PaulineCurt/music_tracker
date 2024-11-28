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
}
