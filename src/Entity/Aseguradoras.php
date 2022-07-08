<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aseguradoras
 *
 * @ORM\Table(name="aseguradoras")
 * @ORM\Entity(repositoryClass="App\Repository\AseguradorasRepository")
 */
class Aseguradoras
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDASEGURADORA", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idaseguradora;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=40, nullable=false)
     */
    private $nombre;

    public function getIdaseguradora(): ?int
    {
        return $this->idaseguradora;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }


}
