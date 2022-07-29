<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Citas
 *
 * @ORM\Table(name="citas", indexes={@ORM\Index(name="idFacultativo_Citas", columns={"IDFACULTATIVO"}), @ORM\Index(name="idUsuario_Citas", columns={"IDPACIENTE"})})
 * @ORM\Entity(repositoryClass="App\Repository\CitasRepository")
 */
class Citas
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDCITA", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcita;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="HORA", type="time", nullable=false)
     */
    private $hora;

    /**
     * @var string
     *
     * @ORM\Column(name="DISPONIBLE", type="string", length=1, nullable=false)
     */
    private $disponible;

    /**
     * @var \Pacientes|null
     *
     * @ORM\ManyToOne(targetEntity="Pacientes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDPACIENTE", referencedColumnName="IDPACIENTE")
     * })
     */
    private $idpaciente;

    /**
     * @var \Facultativos|null
     *
     * @ORM\ManyToOne(targetEntity="Facultativos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDFACULTATIVO", referencedColumnName="IDFACULTATIVO")
     * })
     */
    private $idfacultativo;

    public function getIdcita(): ?int
    {
        return $this->idcita;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getDisponible(): ?string
    {
        return $this->disponible;
    }

    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getIdpaciente(): ?Pacientes
    {
        return $this->idpaciente;
    }

    public function setIdpaciente(?Pacientes $idpaciente): self
    {
        $this->idpaciente = $idpaciente;

        return $this;
    }

    public function getIdfacultativo(): ?Facultativos
    {
        return $this->idfacultativo;
    }

    public function setIdfacultativo(?Facultativos $idfacultativo): self
    {
        $this->idfacultativo = $idfacultativo;

        return $this;
    }


}
