<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vacaciones
 *
 * @ORM\Table(name="vacaciones", indexes={@ORM\Index(name="IDX_CAA83E94F3D48060", columns={"IDFACULTATIVO"})})
 * @ORM\Entity(repositoryClass="App\Repository\VacacionesRepository")
 */
class Vacaciones
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
     * @ORM\Column(name="VACACIONES", type="string", length=1, nullable=false)
     */
    private $vacaciones;

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

    public function getVacaciones(): ?string
    {
        return $this->vacaciones;
    }

    public function setVacaciones(string $vacaciones): self
    {
        $this->vacaciones = $vacaciones;

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
