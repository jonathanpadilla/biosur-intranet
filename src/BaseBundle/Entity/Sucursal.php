<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sucursal
 *
 * @ORM\Table(name="sucursal", indexes={@ORM\Index(name="suc_comuna_fk", columns={"suc_comuna_fk"}), @ORM\Index(name="suc_usuario_fk", columns={"suc_usuario_fk"})})
 * @ORM\Entity
 */
class Sucursal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="suc_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sucIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_nombre", type="string", length=200, nullable=true)
     */
    private $sucNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_nombrecomercial", type="string", length=200, nullable=true)
     */
    private $sucNombrecomercial;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_giro", type="string", length=100, nullable=true)
     */
    private $sucGiro;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_direccion", type="string", length=200, nullable=true)
     */
    private $sucDireccion;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_rut", type="string", length=20, nullable=true)
     */
    private $sucRut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="suc_fecharegistro", type="datetime", nullable=true)
     */
    private $sucFecharegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_configuracion", type="text", length=65535, nullable=true)
     */
    private $sucConfiguracion;

    /**
     * @var string
     *
     * @ORM\Column(name="suc_comentario", type="text", length=65535, nullable=true)
     */
    private $sucComentario;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="suc_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $sucUsuarioFk;

    /**
     * @var \Comuna
     *
     * @ORM\ManyToOne(targetEntity="Comuna")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="suc_comuna_fk", referencedColumnName="com_id_pk")
     * })
     */
    private $sucComunaFk;



    /**
     * Get sucIdPk
     *
     * @return integer
     */
    public function getSucIdPk()
    {
        return $this->sucIdPk;
    }

    /**
     * Set sucNombre
     *
     * @param string $sucNombre
     *
     * @return Sucursal
     */
    public function setSucNombre($sucNombre)
    {
        $this->sucNombre = $sucNombre;

        return $this;
    }

    /**
     * Get sucNombre
     *
     * @return string
     */
    public function getSucNombre()
    {
        return $this->sucNombre;
    }

    /**
     * Set sucNombrecomercial
     *
     * @param string $sucNombrecomercial
     *
     * @return Sucursal
     */
    public function setSucNombrecomercial($sucNombrecomercial)
    {
        $this->sucNombrecomercial = $sucNombrecomercial;

        return $this;
    }

    /**
     * Get sucNombrecomercial
     *
     * @return string
     */
    public function getSucNombrecomercial()
    {
        return $this->sucNombrecomercial;
    }

    /**
     * Set sucGiro
     *
     * @param string $sucGiro
     *
     * @return Sucursal
     */
    public function setSucGiro($sucGiro)
    {
        $this->sucGiro = $sucGiro;

        return $this;
    }

    /**
     * Get sucGiro
     *
     * @return string
     */
    public function getSucGiro()
    {
        return $this->sucGiro;
    }

    /**
     * Set sucDireccion
     *
     * @param string $sucDireccion
     *
     * @return Sucursal
     */
    public function setSucDireccion($sucDireccion)
    {
        $this->sucDireccion = $sucDireccion;

        return $this;
    }

    /**
     * Get sucDireccion
     *
     * @return string
     */
    public function getSucDireccion()
    {
        return $this->sucDireccion;
    }

    /**
     * Set sucRut
     *
     * @param string $sucRut
     *
     * @return Sucursal
     */
    public function setSucRut($sucRut)
    {
        $this->sucRut = $sucRut;

        return $this;
    }

    /**
     * Get sucRut
     *
     * @return string
     */
    public function getSucRut()
    {
        return $this->sucRut;
    }

    /**
     * Set sucFecharegistro
     *
     * @param \DateTime $sucFecharegistro
     *
     * @return Sucursal
     */
    public function setSucFecharegistro($sucFecharegistro)
    {
        $this->sucFecharegistro = $sucFecharegistro;

        return $this;
    }

    /**
     * Get sucFecharegistro
     *
     * @return \DateTime
     */
    public function getSucFecharegistro()
    {
        return $this->sucFecharegistro;
    }

    /**
     * Set sucConfiguracion
     *
     * @param string $sucConfiguracion
     *
     * @return Sucursal
     */
    public function setSucConfiguracion($sucConfiguracion)
    {
        $this->sucConfiguracion = $sucConfiguracion;

        return $this;
    }

    /**
     * Get sucConfiguracion
     *
     * @return string
     */
    public function getSucConfiguracion()
    {
        return $this->sucConfiguracion;
    }

    /**
     * Set sucComentario
     *
     * @param string $sucComentario
     *
     * @return Sucursal
     */
    public function setSucComentario($sucComentario)
    {
        $this->sucComentario = $sucComentario;

        return $this;
    }

    /**
     * Get sucComentario
     *
     * @return string
     */
    public function getSucComentario()
    {
        return $this->sucComentario;
    }

    /**
     * Set sucUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $sucUsuarioFk
     *
     * @return Sucursal
     */
    public function setSucUsuarioFk(\BaseBundle\Entity\Usuario $sucUsuarioFk = null)
    {
        $this->sucUsuarioFk = $sucUsuarioFk;

        return $this;
    }

    /**
     * Get sucUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getSucUsuarioFk()
    {
        return $this->sucUsuarioFk;
    }

    /**
     * Set sucComunaFk
     *
     * @param \BaseBundle\Entity\Comuna $sucComunaFk
     *
     * @return Sucursal
     */
    public function setSucComunaFk(\BaseBundle\Entity\Comuna $sucComunaFk = null)
    {
        $this->sucComunaFk = $sucComunaFk;

        return $this;
    }

    /**
     * Get sucComunaFk
     *
     * @return \BaseBundle\Entity\Comuna
     */
    public function getSucComunaFk()
    {
        return $this->sucComunaFk;
    }
}
