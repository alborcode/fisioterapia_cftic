<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity(repositoryClass="App\Repository\UsuariosRepository")
 */
class Usuarios
{
    /**
     * @var int
     *
     * @ORM\Column(name="IDUSUARIO", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="EMAIL", type="string", length=45, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPOUSUARIO", type="string", length=10, nullable=false)
     */
    private $tipousuario;

    public function getIdusuario(): ?int
    {
        return $this->idusuario;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTipousuario(): ?string
    {
        return $this->tipousuario;
    }

    public function setTipousuario(string $tipousuario): self
    {
        $this->tipousuario = $tipousuario;

        return $this;
    }


}
