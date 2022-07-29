<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rehabilitaciones
 *
 * @ORM\Table(name="rehabilitaciones", indexes={@ORM\Index(name="IDASEGURADORA_REHABILITACION", columns={"IDASEGURADORA"})})
 * @ORM\Entity(repositoryClass="App\Repository\RehabilitacionesRepository")
 */
class Rehabilitaciones
{
    /**
     * @var int
     *
     * @ORM\Column(name="SESIONESTOTALES", type="integer", nullable=false)
     */
    private $sesionestotales;

    /**
     * @var int
     *
     * @ORM\Column(name="SESIONESRESTANTES", type="integer", nullable=false)
     */
    private $sesionesrestantes;

    /**
     * @var string
     *
     * @ORM\Column(name="DETALLE", type="string", length=999, nullable=false)
     */
    private $detalle;

    /**
     * @var \Informes|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Informes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDINFORME", referencedColumnName="IDINFORME")
     * })
     */
    private $idinforme;

    /**
     * @var \Aseguradoras|null
     *
     * @ORM\ManyToOne(targetEntity="Aseguradoras")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IDASEGURADORA", referencedColumnName="IDASEGURADORA")
     * })
     */
    private $idaseguradora;

    public function getSesionestotales(): ?int
    {
        return $this->sesionestotales;
    }

    public function setSesionestotales(int $sesionestotales): self
    {
        $this->sesionestotales = $sesionestotales;

        return $this;
    }

    public function getSesionesrestantes(): ?int
    {
        return $this->sesionesrestantes;
    }

    public function setSesionesrestantes(int $sesionesrestantes): self
    {
        $this->sesionesrestantes = $sesionesrestantes;

        return $this;
    }

    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(string $detalle): self
    {
        $this->detalle = $detalle;

        return $this;
    }

    public function getIdinforme(): ?Informes
    {
        return $this->idinforme;
    }

    public function setIdinforme(?Informes $idinforme): self
    {
        $this->idinforme = $idinforme;

        return $this;
    }

    public function getIdaseguradora(): ?Aseguradoras
    {
        return $this->idaseguradora;
    }

    public function setIdaseguradora(?Aseguradoras $idaseguradora): self
    {
        $this->idaseguradora = $idaseguradora;

        return $this;
    }


}
