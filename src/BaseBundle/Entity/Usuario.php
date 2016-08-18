<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="usu_comuna_fk", columns={"usu_comuna_fk"})})
 * @ORM\Entity
 */
class Usuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="usu_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $usuIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_nombre", type="string", length=100, nullable=true)
     */
    private $usuNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_apellido", type="string", length=100, nullable=true)
     */
    private $usuApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_rut", type="string", length=20, nullable=true)
     */
    private $usuRut;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_direccion", type="string", length=200, nullable=true)
     */
    private $usuDireccion;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_clave", type="string", length=200, nullable=true)
     */
    private $usuClave;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_configuracion", type="text", length=65535, nullable=true)
     */
    private $usuConfiguracion;

    /**
     * @var integer
     *
     * @ORM\Column(name="usu_vinculado", type="integer", nullable=true)
     */
    private $usuVinculado = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="usu_recuperarclave", type="string", length=200, nullable=true)
     */
    private $usuRecuperarclave;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_cookie", type="string", length=100, nullable=true)
     */
    private $usuCookie;

    /**
     * @var integer
     *
     * @ORM\Column(name="usu_tipo", type="integer", nullable=true)
     */
    private $usuTipo = '2';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usu_fecharegistro", type="datetime", nullable=true)
     */
    private $usuFecharegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="usu_comentario", type="text", length=65535, nullable=true)
     */
    private $usuComentario;

    /**
     * @var \Comuna
     *
     * @ORM\ManyToOne(targetEntity="Comuna")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usu_comuna_fk", referencedColumnName="com_id_pk")
     * })
     */
    private $usuComunaFk;



    /**
     * Get usuIdPk
     *
     * @return integer
     */
    public function getUsuIdPk()
    {
        return $this->usuIdPk;
    }

    /**
     * Set usuNombre
     *
     * @param string $usuNombre
     *
     * @return Usuario
     */
    public function setUsuNombre($usuNombre)
    {
        $this->usuNombre = $usuNombre;

        return $this;
    }

    /**
     * Get usuNombre
     *
     * @return string
     */
    public function getUsuNombre()
    {
        return $this->usuNombre;
    }

    /**
     * Set usuApellido
     *
     * @param string $usuApellido
     *
     * @return Usuario
     */
    public function setUsuApellido($usuApellido)
    {
        $this->usuApellido = $usuApellido;

        return $this;
    }

    /**
     * Get usuApellido
     *
     * @return string
     */
    public function getUsuApellido()
    {
        return $this->usuApellido;
    }

    /**
     * Set usuRut
     *
     * @param string $usuRut
     *
     * @return Usuario
     */
    public function setUsuRut($usuRut)
    {
        $this->usuRut = $usuRut;

        return $this;
    }

    /**
     * Get usuRut
     *
     * @return string
     */
    public function getUsuRut()
    {
        return $this->usuRut;
    }

    /**
     * Set usuDireccion
     *
     * @param string $usuDireccion
     *
     * @return Usuario
     */
    public function setUsuDireccion($usuDireccion)
    {
        $this->usuDireccion = $usuDireccion;

        return $this;
    }

    /**
     * Get usuDireccion
     *
     * @return string
     */
    public function getUsuDireccion()
    {
        return $this->usuDireccion;
    }

    /**
     * Set usuClave
     *
     * @param string $usuClave
     *
     * @return Usuario
     */
    public function setUsuClave($usuClave)
    {
        $this->usuClave = $usuClave;

        return $this;
    }

    /**
     * Get usuClave
     *
     * @return string
     */
    public function getUsuClave()
    {
        return $this->usuClave;
    }

    /**
     * Set usuConfiguracion
     *
     * @param string $usuConfiguracion
     *
     * @return Usuario
     */
    public function setUsuConfiguracion($usuConfiguracion)
    {
        $this->usuConfiguracion = $usuConfiguracion;

        return $this;
    }

    /**
     * Get usuConfiguracion
     *
     * @return string
     */
    public function getUsuConfiguracion()
    {
        return $this->usuConfiguracion;
    }

    /**
     * Set usuVinculado
     *
     * @param integer $usuVinculado
     *
     * @return Usuario
     */
    public function setUsuVinculado($usuVinculado)
    {
        $this->usuVinculado = $usuVinculado;

        return $this;
    }

    /**
     * Get usuVinculado
     *
     * @return integer
     */
    public function getUsuVinculado()
    {
        return $this->usuVinculado;
    }

    /**
     * Set usuRecuperarclave
     *
     * @param string $usuRecuperarclave
     *
     * @return Usuario
     */
    public function setUsuRecuperarclave($usuRecuperarclave)
    {
        $this->usuRecuperarclave = $usuRecuperarclave;

        return $this;
    }

    /**
     * Get usuRecuperarclave
     *
     * @return string
     */
    public function getUsuRecuperarclave()
    {
        return $this->usuRecuperarclave;
    }

    /**
     * Set usuCookie
     *
     * @param string $usuCookie
     *
     * @return Usuario
     */
    public function setUsuCookie($usuCookie)
    {
        $this->usuCookie = $usuCookie;

        return $this;
    }

    /**
     * Get usuCookie
     *
     * @return string
     */
    public function getUsuCookie()
    {
        return $this->usuCookie;
    }

    /**
     * Set usuTipo
     *
     * @param integer $usuTipo
     *
     * @return Usuario
     */
    public function setUsuTipo($usuTipo)
    {
        $this->usuTipo = $usuTipo;

        return $this;
    }

    /**
     * Get usuTipo
     *
     * @return integer
     */
    public function getUsuTipo()
    {
        return $this->usuTipo;
    }

    /**
     * Set usuFecharegistro
     *
     * @param \DateTime $usuFecharegistro
     *
     * @return Usuario
     */
    public function setUsuFecharegistro($usuFecharegistro)
    {
        $this->usuFecharegistro = $usuFecharegistro;

        return $this;
    }

    /**
     * Get usuFecharegistro
     *
     * @return \DateTime
     */
    public function getUsuFecharegistro()
    {
        return $this->usuFecharegistro;
    }

    /**
     * Set usuComentario
     *
     * @param string $usuComentario
     *
     * @return Usuario
     */
    public function setUsuComentario($usuComentario)
    {
        $this->usuComentario = $usuComentario;

        return $this;
    }

    /**
     * Get usuComentario
     *
     * @return string
     */
    public function getUsuComentario()
    {
        return $this->usuComentario;
    }

    /**
     * Set usuComunaFk
     *
     * @param \BaseBundle\Entity\Comuna $usuComunaFk
     *
     * @return Usuario
     */
    public function setUsuComunaFk(\BaseBundle\Entity\Comuna $usuComunaFk = null)
    {
        $this->usuComunaFk = $usuComunaFk;

        return $this;
    }

    /**
     * Get usuComunaFk
     *
     * @return \BaseBundle\Entity\Comuna
     */
    public function getUsuComunaFk()
    {
        return $this->usuComunaFk;
    }
}
