<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioLog
 *
 * @ORM\Table(name="usuario_log", indexes={@ORM\Index(name="ulo_sucursal_fk", columns={"ulo_sucursal_fk"}), @ORM\Index(name="ulo_usuario_fk", columns={"ulo_usuario_fk"})})
 * @ORM\Entity
 */
class UsuarioLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ulo_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uloIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="ulo_descripcion", type="text", length=65535, nullable=true)
     */
    private $uloDescripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ulo_fecha", type="datetime", nullable=true)
     */
    private $uloFecha;

    /**
     * @var string
     *
     * @ORM\Column(name="ulo_comentario", type="text", length=65535, nullable=true)
     */
    private $uloComentario;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ulo_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $uloSucursalFk;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ulo_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $uloUsuarioFk;



    /**
     * Get uloIdPk
     *
     * @return integer
     */
    public function getUloIdPk()
    {
        return $this->uloIdPk;
    }

    /**
     * Set uloDescripcion
     *
     * @param string $uloDescripcion
     *
     * @return UsuarioLog
     */
    public function setUloDescripcion($uloDescripcion)
    {
        $this->uloDescripcion = $uloDescripcion;

        return $this;
    }

    /**
     * Get uloDescripcion
     *
     * @return string
     */
    public function getUloDescripcion()
    {
        return $this->uloDescripcion;
    }

    /**
     * Set uloFecha
     *
     * @param \DateTime $uloFecha
     *
     * @return UsuarioLog
     */
    public function setUloFecha($uloFecha)
    {
        $this->uloFecha = $uloFecha;

        return $this;
    }

    /**
     * Get uloFecha
     *
     * @return \DateTime
     */
    public function getUloFecha()
    {
        return $this->uloFecha;
    }

    /**
     * Set uloComentario
     *
     * @param string $uloComentario
     *
     * @return UsuarioLog
     */
    public function setUloComentario($uloComentario)
    {
        $this->uloComentario = $uloComentario;

        return $this;
    }

    /**
     * Get uloComentario
     *
     * @return string
     */
    public function getUloComentario()
    {
        return $this->uloComentario;
    }

    /**
     * Set uloSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $uloSucursalFk
     *
     * @return UsuarioLog
     */
    public function setUloSucursalFk(\BaseBundle\Entity\Sucursal $uloSucursalFk = null)
    {
        $this->uloSucursalFk = $uloSucursalFk;

        return $this;
    }

    /**
     * Get uloSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getUloSucursalFk()
    {
        return $this->uloSucursalFk;
    }

    /**
     * Set uloUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $uloUsuarioFk
     *
     * @return UsuarioLog
     */
    public function setUloUsuarioFk(\BaseBundle\Entity\Usuario $uloUsuarioFk = null)
    {
        $this->uloUsuarioFk = $uloUsuarioFk;

        return $this;
    }

    /**
     * Get uloUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getUloUsuarioFk()
    {
        return $this->uloUsuarioFk;
    }
}
