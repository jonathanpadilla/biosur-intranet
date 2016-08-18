<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BannosTipo
 *
 * @ORM\Table(name="bannos_tipo", indexes={@ORM\Index(name="bti_sucursal_fk", columns={"bti_sucursal_fk"})})
 * @ORM\Entity
 */
class BannosTipo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="bti_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $btiIdPk;

    /**
     * @var integer
     *
     * @ORM\Column(name="bti_producto", type="integer", nullable=true)
     */
    private $btiProducto;

    /**
     * @var string
     *
     * @ORM\Column(name="bti_nombre", type="string", length=100, nullable=true)
     */
    private $btiNombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="bti_activo", type="integer", nullable=true)
     */
    private $btiActivo = '1';

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bti_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $btiSucursalFk;



    /**
     * Get btiIdPk
     *
     * @return integer
     */
    public function getBtiIdPk()
    {
        return $this->btiIdPk;
    }

    /**
     * Set btiProducto
     *
     * @param integer $btiProducto
     *
     * @return BannosTipo
     */
    public function setBtiProducto($btiProducto)
    {
        $this->btiProducto = $btiProducto;

        return $this;
    }

    /**
     * Get btiProducto
     *
     * @return integer
     */
    public function getBtiProducto()
    {
        return $this->btiProducto;
    }

    /**
     * Set btiNombre
     *
     * @param string $btiNombre
     *
     * @return BannosTipo
     */
    public function setBtiNombre($btiNombre)
    {
        $this->btiNombre = $btiNombre;

        return $this;
    }

    /**
     * Get btiNombre
     *
     * @return string
     */
    public function getBtiNombre()
    {
        return $this->btiNombre;
    }

    /**
     * Set btiActivo
     *
     * @param integer $btiActivo
     *
     * @return BannosTipo
     */
    public function setBtiActivo($btiActivo)
    {
        $this->btiActivo = $btiActivo;

        return $this;
    }

    /**
     * Get btiActivo
     *
     * @return integer
     */
    public function getBtiActivo()
    {
        return $this->btiActivo;
    }

    /**
     * Set btiSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $btiSucursalFk
     *
     * @return BannosTipo
     */
    public function setBtiSucursalFk(\BaseBundle\Entity\Sucursal $btiSucursalFk = null)
    {
        $this->btiSucursalFk = $btiSucursalFk;

        return $this;
    }

    /**
     * Get btiSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getBtiSucursalFk()
    {
        return $this->btiSucursalFk;
    }
}
