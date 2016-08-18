<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente", indexes={@ORM\Index(name="cli_sucursal_fk", columns={"cli_sucursal_fk"}), @ORM\Index(name="cli_comuna_fk", columns={"cli_comuna_fk"}), @ORM\Index(name="cli_usuario_fk", columns={"cli_usuario_fk"})})
 * @ORM\Entity
 */
class Cliente
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cli_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cliIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_nombre", type="string", length=100, nullable=true)
     */
    private $cliNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_rut", type="string", length=30, nullable=true)
     */
    private $cliRut;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_giro", type="string", length=100, nullable=true)
     */
    private $cliGiro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cli_fecharegistro", type="datetime", nullable=true)
     */
    private $cliFecharegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_direccion", type="string", length=100, nullable=true)
     */
    private $cliDireccion;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_comentario", type="text", length=65535, nullable=true)
     */
    private $cliComentario;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cli_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $cliSucursalFk;

    /**
     * @var \Comuna
     *
     * @ORM\ManyToOne(targetEntity="Comuna")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cli_comuna_fk", referencedColumnName="com_id_pk")
     * })
     */
    private $cliComunaFk;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cli_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $cliUsuarioFk;



    /**
     * Get cliIdPk
     *
     * @return integer
     */
    public function getCliIdPk()
    {
        return $this->cliIdPk;
    }

    /**
     * Set cliNombre
     *
     * @param string $cliNombre
     *
     * @return Cliente
     */
    public function setCliNombre($cliNombre)
    {
        $this->cliNombre = $cliNombre;

        return $this;
    }

    /**
     * Get cliNombre
     *
     * @return string
     */
    public function getCliNombre()
    {
        return $this->cliNombre;
    }

    /**
     * Set cliRut
     *
     * @param string $cliRut
     *
     * @return Cliente
     */
    public function setCliRut($cliRut)
    {
        $this->cliRut = $cliRut;

        return $this;
    }

    /**
     * Get cliRut
     *
     * @return string
     */
    public function getCliRut()
    {
        return $this->cliRut;
    }

    /**
     * Set cliGiro
     *
     * @param string $cliGiro
     *
     * @return Cliente
     */
    public function setCliGiro($cliGiro)
    {
        $this->cliGiro = $cliGiro;

        return $this;
    }

    /**
     * Get cliGiro
     *
     * @return string
     */
    public function getCliGiro()
    {
        return $this->cliGiro;
    }

    /**
     * Set cliFecharegistro
     *
     * @param \DateTime $cliFecharegistro
     *
     * @return Cliente
     */
    public function setCliFecharegistro($cliFecharegistro)
    {
        $this->cliFecharegistro = $cliFecharegistro;

        return $this;
    }

    /**
     * Get cliFecharegistro
     *
     * @return \DateTime
     */
    public function getCliFecharegistro()
    {
        return $this->cliFecharegistro;
    }

    /**
     * Set cliDireccion
     *
     * @param string $cliDireccion
     *
     * @return Cliente
     */
    public function setCliDireccion($cliDireccion)
    {
        $this->cliDireccion = $cliDireccion;

        return $this;
    }

    /**
     * Get cliDireccion
     *
     * @return string
     */
    public function getCliDireccion()
    {
        return $this->cliDireccion;
    }

    /**
     * Set cliComentario
     *
     * @param string $cliComentario
     *
     * @return Cliente
     */
    public function setCliComentario($cliComentario)
    {
        $this->cliComentario = $cliComentario;

        return $this;
    }

    /**
     * Get cliComentario
     *
     * @return string
     */
    public function getCliComentario()
    {
        return $this->cliComentario;
    }

    /**
     * Set cliSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $cliSucursalFk
     *
     * @return Cliente
     */
    public function setCliSucursalFk(\BaseBundle\Entity\Sucursal $cliSucursalFk = null)
    {
        $this->cliSucursalFk = $cliSucursalFk;

        return $this;
    }

    /**
     * Get cliSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getCliSucursalFk()
    {
        return $this->cliSucursalFk;
    }

    /**
     * Set cliComunaFk
     *
     * @param \BaseBundle\Entity\Comuna $cliComunaFk
     *
     * @return Cliente
     */
    public function setCliComunaFk(\BaseBundle\Entity\Comuna $cliComunaFk = null)
    {
        $this->cliComunaFk = $cliComunaFk;

        return $this;
    }

    /**
     * Get cliComunaFk
     *
     * @return \BaseBundle\Entity\Comuna
     */
    public function getCliComunaFk()
    {
        return $this->cliComunaFk;
    }

    /**
     * Set cliUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $cliUsuarioFk
     *
     * @return Cliente
     */
    public function setCliUsuarioFk(\BaseBundle\Entity\Usuario $cliUsuarioFk = null)
    {
        $this->cliUsuarioFk = $cliUsuarioFk;

        return $this;
    }

    /**
     * Get cliUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getCliUsuarioFk()
    {
        return $this->cliUsuarioFk;
    }
}
