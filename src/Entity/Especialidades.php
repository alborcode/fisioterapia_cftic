<?php

namespace App\Entity;

use App\Repository\EspecialidadesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EspecialidadesRepository::class)]
class Especialidades
{

    #[ORM\Column]
    private ?int $idespecialidad = null;

    #[ORM\Id]
    #[ORM\Column(length: 60)]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'La Especialidad debe tener al menos {{ limit }} caracteres de longitud',
        maxMessage: 'La Especialidad no puede tener más de {{ limit }} caracteres',
    )]
    private ?string $especialidad = null;

    public function getIdespecialidad(): ?int
    {
        return $this->idespecialidad;
    }

    public function getEspecialidad(): ?string
    {
        return $this->especialidad;
    }

    public function setEspecialidad(string $especialidad): self
    {
        $this->especialidad = $especialidad;

        return $this;
    }

}
