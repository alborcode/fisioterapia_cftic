<?php

namespace App\Entity;

use App\Repository\ProvinciasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProvinciasRepository::class)]
class Provincias
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $idprovincia = null;

    #[ORM\Id]
    #[ORM\Column(length: 60)]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'La Provincia debe tener al menos {{ limit }} caracteres de longitud',
        maxMessage: 'La Provincia no puede tener más de {{ limit }} caracteres',
    )]
    private ?string $provincia = null;

    public function getIdprovincia(): ?int
    {
        return $this->idprovincia;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }
}
