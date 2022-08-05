<?php

namespace App\Entity;

use App\Repository\FacultativosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FacultativosRepository::class)]
class Facultativos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idfacultativo = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    private ?string $nombre = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    private ?string $apellido1 = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $apellido2 = null;

    #[ORM\Column(length: 15)]
    private ?string $telefono = null;

    #[ORM\Column(length: 20)]
    private ?string $especialidad = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, name:"idusuario", referencedColumnName:"idusuario")]
    private ?Usuarios $idusuario = null;

    public function getIdfacultativo(): ?int
    {
        return $this->idfacultativo;
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

    public function getApellido1(): ?string
    {
        return $this->apellido1;
    }

    public function setApellido1(string $apellido1): self
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    public function getApellido2(): ?string
    {
        return $this->apellido2;
    }

    public function setApellido2(?string $apellido2): self
    {
        $this->apellido2 = $apellido2;

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

    public function getEspecialidad(): ?string
    {
        return $this->especialidad;
    }

    public function setEspecialidad(string $especialidad): self
    {
        $this->especialidad = $especialidad;

        return $this;
    }

    public function getIdusuario(): ?Usuarios
    {
        return $this->idusuario;
    }

    public function setIdusuario(Usuarios $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }
}
