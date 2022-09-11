<?php

namespace App\Entity;

use App\Repository\TurnosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TurnosRepository::class)]
class Turnos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idturno = null;

    // #[ORM\Column(type: Types::DATE_MUTABLE)]
    // #[Assert\Date]
    // private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(['DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'])]
    private ?string $diasemana = null;

    #[ORM\Column]
    private ?int $horainicio = null;

    #[ORM\Column]
    private ?int $horafin = null;

    // #[ORM\Column]
    // #[Assert\Choice(['SI', 'NO'])]
    // private ?bool $disponible = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idfacultativo", referencedColumnName:"idfacultativo")]
    private ?Facultativos $idfacultativo = null;

    public function getIdturno(): ?int
    {
        return $this->idturno;
    }

    // public function getDiasemana(): ?\DateTimeInterface
    // {
    //     return $this->fecha;
    // }

    // public function setDiasemana(\DateTimeInterface $fecha): self
    // {
    //     $this->fecha = $fecha;

    //     return $this;
    // }

    public function getDiasemana(): ?string
    {
        return $this->diasemana;
    }

    public function setDiasemana(?string $diasemana): self
    {
        $this->diasemana = $diasemana;

        return $this;
    }

    public function getHorainicio(): ?int
    {
        return $this->horainicio;
    }

    public function setHorainicio(?int $horainicio): self
    {
        $this->horainicio = $horainicio;

        return $this;
    }

    public function getHorafin(): ?int
    {
        return $this->horafin;
    }

    public function setHorafin(?int $horafin): self
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
