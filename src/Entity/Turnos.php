<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Turnos
 *
 * @ORM\Table(name="turnos", indexes={@ORM\Index(name="IDX_B8555818F3D48060", columns={"IDFACULTATIVO"})})
 * @ORM\Entity(repositoryClass="App\Repository\TurnosRepository")
 */
class Turnos
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA", type="date", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="DISPONIBLE", type="string", length=1, nullable=false)
     */
    private $disponible;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="HORAINICIO", type="time", nullable=false)
     */
    private $horainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="HORAFIN", type="time", nullable=false)
     */
    private $horafin;

    /**
     * @var \Facultativos|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Facultativos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDFACULTATIVO", referencedColumnName="IDFACULTATIVO")
     * })
     */
    private $idfacultativo;

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
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

    public function getHorainicio(): ?\DateTimeInterface
    {
        return $this->horainicio;
    }

    public function setHorainicio(\DateTimeInterface $horainicio): self
    {
        $this->horainicio = $horainicio;

        return $this;
    }

    public function getHorafin(): ?\DateTimeInterface
    {
        return $this->horafin;
    }

    public function setHorafin(\DateTimeInterface $horafin): self
    {
        $this->horafin = $horafin;

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
