<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Informe
 *
 * @ORM\Table(name="informe", indexes={@ORM\Index(name="idUsuario_Informe", columns={"IDPACIENTE"}), @ORM\Index(name="idFacultativo_Informe", columns={"IDFACULTATIVO"})})
 * @ORM\Entity(repositoryClass="App\Repository\InformeRepository")
 */
class Informe
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDINFORME", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idinforme;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="HORA", type="integer", nullable=false)
     */
    private $hora;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DETALLE", type="string", length=999, nullable=true)
     */
    private $detalle;

    /**
     * @var \Pacientes
     *
     * @ORM\ManyToOne(targetEntity="Pacientes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDPACIENTE", referencedColumnName="IDPACIENTE")
     * })
     */
    private $idpaciente;

    /**
     * @var \Facultativos
     *
     * @ORM\ManyToOne(targetEntity="Facultativos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDFACULTATIVO", referencedColumnName="IDFACULTATIVO")
     * })
     */
    private $idfacultativo;

    public function getIdinforme(): ?int
    {
        return $this->idinforme;
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

    public function getHora(): ?int
    {
        return $this->hora;
    }

    public function setHora(int $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(?string $detalle): self
    {
        $this->detalle = $detalle;

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
