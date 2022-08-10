<?php

namespace App\Entity;

use App\Repository\ProvinciasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvinciasRepository::class)]
class Provincias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idprovincia = null;

    #[ORM\Column(length: 60)]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'El nombre debe tener al menos {{ limit }} caracteres de longitud',
        maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres',
    )]
    private ?string $nombre = null;

    public function getIdprovincia(): ?int
    {
        return $this->idprovincia;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
