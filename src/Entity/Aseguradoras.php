<?php

namespace App\Entity;

use App\Repository\AseguradorasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AseguradorasRepository::class)]
class Aseguradoras
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idaseguradora = null;

    #[ORM\Column(length: 80)]
    private ?string $nombre = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(nullable: true)]
    private ?int $codigopostal = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $poblacion = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $provincia = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    public function getIdaseguradora(): ?int
    {
        return $this->idaseguradora;
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

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCodigopostal(): ?int
    {
        return $this->codigopostal;
    }

    public function setCodigopostal(?int $codigopostal): self
    {
        $this->codigopostal = $codigopostal;

        return $this;
    }

    public function getPoblacion(): ?string
    {
        return $this->poblacion;
    }

    public function setPoblacion(?string $poblacion): self
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(?string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
