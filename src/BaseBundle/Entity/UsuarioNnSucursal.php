<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioNnSucursal
 *
 * @ORM\Table(name="usuario_nn_sucursal", indexes={@ORM\Index(name="unns_sucursal_fk", columns={"unns_sucursal_fk"}), @ORM\Index(name="unns_usuario_fk", columns={"unns_usuario_fk"})})
 * @ORM\Entity
 */
class UsuarioNnSucursal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="unns_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $unnsIdPk;

    /**
     * @var integer
     *
     * @ORM\Column(name="unns_habilitado", type="integer", nullable=false)
     */
    private $unnsHabilitado = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="unns_fecharegistro", type="datetime", nullable=false)
     */
    private $unnsFecharegistro;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unns_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $unnsSucursalFk;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unns_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $unnsUsuarioFk;



    /**
     * Get unnsIdPk
     *
     * @return integer
     */
    public function getUnnsIdPk()
    {
        return $this->unnsIdPk;
    }

    /**
     * Set unnsHabilitado
     *
     * @param integer $unnsHabilitado
     *
     * @return UsuarioNnSucursal
     */
    public function setUnnsHabilitado($unnsHabilitado)
    {
        $this->unnsHabilitado = $unnsHabilitado;

        return $this;
    }

    /**
     * Get unnsHabilitado
     *
     * @return integer
     */
    public function getUnnsHabilitado()
    {
        return $this->unnsHabilitado;
    }

    /**
     * Set unnsFecharegistro
     *
     * @param \DateTime $unnsFecharegistro
     *
     * @return UsuarioNnSucursal
     */
    public function setUnnsFecharegistro($unnsFecharegistro)
    {
        $this->unnsFecharegistro = $unnsFecharegistro;

        return $this;
    }

    /**
     * Get unnsFecharegistro
     *
     * @return \DateTime
     */
    public function getUnnsFecharegistro()
    {
        return $this->unnsFecharegistro;
    }

    /**
     * Set unnsSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $unnsSucursalFk
     *
     * @return UsuarioNnSucursal
     */
    public function setUnnsSucursalFk(\BaseBundle\Entity\Sucursal $unnsSucursalFk = null)
    {
        $this->unnsSucursalFk = $unnsSucursalFk;

        return $this;
    }

    /**
     * Get unnsSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getUnnsSucursalFk()
    {
        return $this->unnsSucursalFk;
    }

    /**
     * Set unnsUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $unnsUsuarioFk
     *
     * @return UsuarioNnSucursal
     */
    public function setUnnsUsuarioFk(\BaseBundle\Entity\Usuario $unnsUsuarioFk = null)
    {
        $this->unnsUsuarioFk = $unnsUsuarioFk;

        return $this;
    }

    /**
     * Get unnsUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getUnnsUsuarioFk()
    {
        return $this->unnsUsuarioFk;
    }
}
