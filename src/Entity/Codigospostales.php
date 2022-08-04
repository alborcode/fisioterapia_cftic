<?php

namespace App\Entity;

use App\Repository\CodigospostalesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodigospostalesRepository::class)]
class Codigospostales
{
    // No hacemos autoincremental el CP ya que tiene un valor fijo
    //     #[ORM\GeneratedValue]
    #[ORM\Id]
    #[ORM\Column]
    private ?int $idcp = null;

    #[ORM\Column(length: 22)]
    private ?string $provincia = null;

    #[ORM\Column(length: 47)]
    private ?string $poblacion = null;

    public function getIdcp(): ?int
    {
        return $this->idcp;
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

    public function getPoblacion(): ?string
    {
        return $this->poblacion;
    }

    public function setPoblacion(string $poblacion): self
    {
        $this->poblacion = $poblacion;

        return $this;
    }
}
