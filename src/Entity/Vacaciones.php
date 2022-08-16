<?php

namespace App\Entity;

use App\Repository\VacacionesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VacacionesRepository::class)]
class Vacaciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idvacaciones = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    #[Assert\Choice(['SI', 'NO'])]
    private ?bool $dianotrabajado = null;

    #[ORM\Column]
    #[Assert\Choice(['SI', 'NO'])]
    private ?bool $diadebaja = null;

    // Se modifica JoinColumn para añadir el name ya que no es id se cambio
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"idfacultativo", referencedColumnName:"idfacultativo")]
    private ?Facultativos $idfacultativo = null;

    public function getIdvacaciones(): ?int
    {
        return $this->idvaciones;
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

    public function isDianotrabajado(): ?bool
    {
        return $this->dianotrabajado;
    }

    public function setDianotrabajado(bool $dianotrabajado): self
    {
        $this->dianotrabajado = $dianotrabajado;

        return $this;
    }

    public function isDiadebaja(): ?bool
    {
        return $this->diadebaja;
    }

    public function setDiadebaja(bool $diadebaja): self
    {
        $this->diadebaja = $diadebaja;

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
