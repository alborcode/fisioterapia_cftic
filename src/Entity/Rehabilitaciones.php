<?php

namespace App\Entity;

use App\Repository\RehabilitacionesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RehabilitacionesRepository::class)]
class Rehabilitaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idrehabilitacion = null;

    #[ORM\Column]
    private ?int $sesionestotales = null;

    #[ORM\Column]
    private ?int $sesionesrestantes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechainicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ultimasesion = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observaciones = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idpaciente", referencedColumnName:"idpaciente")]
    private ?Pacientes $idpaciente = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idaseguradora", referencedColumnName:"idaseguradora")]
    private ?Aseguradoras $idaseguradora = null;

    public function getIdrehabilitacion(): ?int
    {
        return $this->idrehabilitacion;
    }

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

    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

    public function setFechainicio(\DateTimeInterface $fechainicio): self
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    public function getUltimasesion(): ?\DateTimeInterface
    {
        return $this->ultimasesion;
    }

    public function setUltimasesion(?\DateTimeInterface $ultimasesion): self
    {
        $this->ultimasesion = $ultimasesion;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

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
