<?php

namespace App\Entity;

use App\Repository\InformesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InformesRepository::class)]
class Informes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idinforme = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(['SESION', 'URGENCIA'])]
    private ?string $tipoinforme = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detalle = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $anexo = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idfacultativo", referencedColumnName:"idfacultativo")]
    private ?Facultativos $idfacultativo = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idpaciente", referencedColumnName:"idpaciente")]
    private ?Pacientes $idpaciente = null;

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

    public function getTipoinforme(): ?string
    {
        return $this->tipoinforme;
    }

    public function setTipoinforme(string $tipoinforme): self
    {
        $this->tipoinforme = $tipoinforme;

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

    public function getIdfacultativo(): ?Facultativos
    {
        return $this->idfacultativo;
    }

    public function setIdfacultativo(?Facultativos $idfacultativo): self
    {
        $this->idfacultativo = $idfacultativo;

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
}
