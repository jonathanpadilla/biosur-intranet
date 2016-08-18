<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacto
 *
 * @ORM\Table(name="contacto", indexes={@ORM\Index(name="con_tipo_fk", columns={"con_tipo_fk"}), @ORM\Index(name="cti_sucursal_fk", columns={"con_sucursal_fk"}), @ORM\Index(name="cti_usuario_fk", columns={"con_usuario_fk"}), @ORM\Index(name="cti_cliente_fk", columns={"con_cliente_fk"})})
 * @ORM\Entity
 */
class Contacto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="con_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $conIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="con_detalle", type="string", length=50, nullable=true)
     */
    private $conDetalle;

    /**
     * @var string
     *
     * @ORM\Column(name="con_nombrepersona", type="string", length=100, nullable=true)
     */
    private $conNombrepersona;

    /**
     * @var \ContactoTipo
     *
     * @ORM\ManyToOne(targetEntity="ContactoTipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="con_tipo_fk", referencedColumnName="cti_id_pk")
     * })
     */
    private $conTipoFk;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="con_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $conSucursalFk;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="con_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $conUsuarioFk;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="con_cliente_fk", referencedColumnName="cli_id_pk")
     * })
     */
    private $conClienteFk;



    /**
     * Get conIdPk
     *
     * @return integer
     */
    public function getConIdPk()
    {
        return $this->conIdPk;
    }

    /**
     * Set conDetalle
     *
     * @param string $conDetalle
     *
     * @return Contacto
     */
    public function setConDetalle($conDetalle)
    {
        $this->conDetalle = $conDetalle;

        return $this;
    }

    /**
     * Get conDetalle
     *
     * @return string
     */
    public function getConDetalle()
    {
        return $this->conDetalle;
    }

    /**
     * Set conNombrepersona
     *
     * @param string $conNombrepersona
     *
     * @return Contacto
     */
    public function setConNombrepersona($conNombrepersona)
    {
        $this->conNombrepersona = $conNombrepersona;

        return $this;
    }

    /**
     * Get conNombrepersona
     *
     * @return string
     */
    public function getConNombrepersona()
    {
        return $this->conNombrepersona;
    }

    /**
     * Set conTipoFk
     *
     * @param \BaseBundle\Entity\ContactoTipo $conTipoFk
     *
     * @return Contacto
     */
    public function setConTipoFk(\BaseBundle\Entity\ContactoTipo $conTipoFk = null)
    {
        $this->conTipoFk = $conTipoFk;

        return $this;
    }

    /**
     * Get conTipoFk
     *
     * @return \BaseBundle\Entity\ContactoTipo
     */
    public function getConTipoFk()
    {
        return $this->conTipoFk;
    }

    /**
     * Set conSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $conSucursalFk
     *
     * @return Contacto
     */
    public function setConSucursalFk(\BaseBundle\Entity\Sucursal $conSucursalFk = null)
    {
        $this->conSucursalFk = $conSucursalFk;

        return $this;
    }

    /**
     * Get conSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getConSucursalFk()
    {
        return $this->conSucursalFk;
    }

    /**
     * Set conUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $conUsuarioFk
     *
     * @return Contacto
     */
    public function setConUsuarioFk(\BaseBundle\Entity\Usuario $conUsuarioFk = null)
    {
        $this->conUsuarioFk = $conUsuarioFk;

        return $this;
    }

    /**
     * Get conUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getConUsuarioFk()
    {
        return $this->conUsuarioFk;
    }

    /**
     * Set conClienteFk
     *
     * @param \BaseBundle\Entity\Cliente $conClienteFk
     *
     * @return Contacto
     */
    public function setConClienteFk(\BaseBundle\Entity\Cliente $conClienteFk = null)
    {
        $this->conClienteFk = $conClienteFk;

        return $this;
    }

    /**
     * Get conClienteFk
     *
     * @return \BaseBundle\Entity\Cliente
     */
    public function getConClienteFk()
    {
        return $this->conClienteFk;
    }
}
