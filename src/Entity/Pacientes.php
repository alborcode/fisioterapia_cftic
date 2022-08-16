<?php

namespace App\Entity;

use App\Repository\PacientesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PacientesRepository::class)]
class Pacientes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idpaciente = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'El nombre debe tener al menos {{ limit }} caracteres de longitud',
        maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres',
    )]
    private ?string $nombre = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'El apellido debe tener al menos {{ limit }} caracteres de longitud',
        maxMessage: 'El apellido no puede tener más de {{ limit }} caracteres',
    )]
    private ?string $apellido1 = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $apellido2 = null;

    #[ORM\Column(length: 15)]
    private ?string $telefono = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(nullable: true)]
    private ?int $codigopostal = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $poblacion = null;

    // Se modifica para añadir JoinColumn de Nombre de Provincia
    //#[ORM\Column(length: 60, nullable: true)]
    //private ?string $provincia = null;
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"provincia", referencedColumnName:"provincia")]
    private ?Provincias $provincia = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, name:"idusuario", referencedColumnName:"idusuario")]
    private ?Usuarios $idusuario = null;

    public function getIdpaciente(): ?int
    {
        return $this->idpaciente;
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

    // public function getProvincia(): ?string
    // {
    //     return $this->provincia;
    // }

    // public function setProvincia(?string $provincia): self
    // {
    //     $this->provincia = $provincia;

    //     return $this;
    // }

    public function getProvincia(): ?Provincias
    {
        return $this->provincia;
    }

    public function setProvincia(Provincias $provincia): self
    {
        $this->provincia = $provincia;

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
