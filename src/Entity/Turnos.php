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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\Time]
    private ?\DateTimeInterface $horainicio = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\Time]
    private ?\DateTimeInterface $horafin = null;

    #[ORM\Column(length: 10)]
    #[Assert\Choice(['MAÑANA', 'TARDE'])]
    private ?string $turno = null;

    #[ORM\Column]
    #[Assert\Choice(['SI', 'NO'])]
    private ?bool $disponible = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idfacultativo", referencedColumnName:"idfacultativo")]
    private ?Facultativos $idfacultativo = null;

    public function getIdturno(): ?int
    {
        return $this->idturno;
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

    public function getTurno(): ?string
    {
        return $this->turno;
    }

    public function setTurno(string $turno): self
    {
        $this->turno = $turno;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

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
