<?php

namespace App\Entity;

use App\Repository\CodigospostalesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CodigospostalesRepository::class)]
//#[ORM\Entity(uniqueConstraints:{@UniqueConstraint(name="indiceunico", columns={"poblacion", "codigopostal"})]
class Codigospostales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 47)]
    private ?string $poblacion = null;

    #[ORM\Column(length: 5)]
    private ?string $codigopostal = null;

    #[ORM\Column(length: 22)]
    private ?string $provincia = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoblacion(): ?string
    {
        return $this->poblacion;
    }

    public function setPoblacion(string $poblacion): self
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    public function getCodigopostal(): ?string
    {
        return $this->codigopostal;
    }

    public function setCodigopostal(string $poblacion): self
    {
        $this->codigopostal = $codigopostal;

        return $this;
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
