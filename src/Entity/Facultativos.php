<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facultativos
 *
 * @ORM\Table(name="facultativos", indexes={@ORM\Index(name="IDUSUARIO_FACULTATIVOS", columns={"IDUSUARIO"})})
 * @ORM\Entity(repositoryClass="App\Repository\FacultativosRepository")
 */
class Facultativos
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDFACULTATIVO", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfacultativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ESPECIALIDAD", type="string", length=10, nullable=false)
     */
    private $especialidad;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=40, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="APELLIDO1", type="string", length=40, nullable=false)
     */
    private $apellido1;

    /**
     * @var string
     *
     * @ORM\Column(name="APELLIDO2", type="string", length=40, nullable=false)
     */
    private $apellido2;

    /**
     * @var int
     *
     * @ORM\Column(name="TELEFONO", type="integer", nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="MIME", type="string", length=255, nullable=false)
     */
    private $mime;

    /**
     * @var string
     *
     * @ORM\Column(name="FOTO", type="blob", length=65535, nullable=false)
     */
    private $foto;

    /**
     * @var \Usuarios|null
     *
     * @ORM\OneToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDUSUARIO", referencedColumnName="IDUSUARIO")
     * })
     */
    private $idusuario;

    public function getIdfacultativo(): ?int
    {
        return $this->idfacultativo;
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

    public function setApellido2(string $apellido2): self
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(string $mime): self
    {
        $this->mime = $mime;

        return $this;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getIdusuario(): ?Usuarios
    {
        return $this->idusuario;
    }

    public function setIdusuario(?Usuarios $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }


}
