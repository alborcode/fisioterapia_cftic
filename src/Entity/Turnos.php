<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Turnos
 *
 * @ORM\Table(name="turnos", indexes={@ORM\Index(name="IDX_B855581853040D52", columns={"idFacultativo"})})
 * @ORM\Entity(repositoryClass="App\Repository\TurnosRepository")
 */
class Turnos
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDFACULTATIVO", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idfacultativo;

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
     * @ORM\Column(name="DISPONIBLE", type="string", length=1, nullable=false, options={"default"="S"})
     */
    private $disponible = 'S';

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
     * @var \Facultativos
     *
     * @ORM\ManyToOne(targetEntity="Facultativos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idFacultativo", referencedColumnName="idFACULTATIVO")
     * })
     */
    private $idfacultativo2;

    public function getIdfacultativo(): ?int
    {
        return $this->idfacultativo;
    }

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

    public function getIdfacultativo2(): ?Facultativos
    {
        return $this->idfacultativo2;
    }

    public function setIdfacultativo2(?Facultativos $idfacultativo2): self
    {
        $this->idfacultativo2 = $idfacultativo2;

        return $this;
    }


}
