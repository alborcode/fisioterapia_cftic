<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pacientes
 *
 * @ORM\Table(name="pacientes", indexes={@ORM\Index(name="IDUSUARIO_PACIENTES", columns={"IDUSUARIO"})})
 * @ORM\Entity(repositoryClass="App\Repository\PacientesRepository")
 */
class Pacientes
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDPACIENTE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpaciente;

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
     * @var string
     *
     * @ORM\Column(name="DIRECCION", type="string", length=40, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="POBLACION", type="string", length=40, nullable=false)
     */
    private $poblacion;

    /**
     * @var int
     *
     * @ORM\Column(name="CP", type="integer", nullable=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="PROVINCIA", type="string", length=40, nullable=false)
     */
    private $provincia;

    /**
     * @var int
     *
     * @ORM\Column(name="TELEFONO", type="integer", nullable=false)
     */
    private $telefono;

    /**
     * @var \User|null
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDUSUARIO", referencedColumnName="id")
     * })
     */
    private $idusuario;

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

    public function setApellido2(string $apellido2): self
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

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

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): self
    {
        $this->cp = $cp;

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

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getIdusuario(): ?User
    {
        return $this->idusuario;
    }

    public function setIdusuario(?User $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }


}
