<?php

namespace App\Entity;

use App\Repository\EspecialidadesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EspecialidadesRepository::class)]
class Especialidades
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idespecialidad = null;

    #[ORM\Column(length: 60)]
    private ?string $nombre = null;

    public function getIdespecialidad(): ?int
    {
        return $this->idespecialidad;
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
