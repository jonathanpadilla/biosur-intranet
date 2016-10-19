<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="producto", indexes={@ORM\Index(name="pro_sucursal", columns={"pro_sucursal"})})
 * @ORM\Entity
 */
class Producto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="pro_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $proIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="pro_nombre", type="string", length=100, nullable=true)
     */
    private $proNombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="pro_cantidad", type="integer", nullable=true)
     */
    private $proCantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="pro_activo", type="integer", nullable=true)
     */
    private $proActivo;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pro_sucursal", referencedColumnName="suc_id_pk")
     * })
     */
    private $proSucursal;



    /**
     * Get proIdPk
     *
     * @return integer
     */
    public function getProIdPk()
    {
        return $this->proIdPk;
    }

    /**
     * Set proNombre
     *
     * @param string $proNombre
     *
     * @return Producto
     */
    public function setProNombre($proNombre)
    {
        $this->proNombre = $proNombre;

        return $this;
    }

    /**
     * Get proNombre
     *
     * @return string
     */
    public function getProNombre()
    {
        return $this->proNombre;
    }

    /**
     * Set proCantidad
     *
     * @param integer $proCantidad
     *
     * @return Producto
     */
    public function setProCantidad($proCantidad)
    {
        $this->proCantidad = $proCantidad;

        return $this;
    }

    /**
     * Get proCantidad
     *
     * @return integer
     */
    public function getProCantidad()
    {
        return $this->proCantidad;
    }

    /**
     * Set proActivo
     *
     * @param integer $proActivo
     *
     * @return Producto
     */
    public function setProActivo($proActivo)
    {
        $this->proActivo = $proActivo;

        return $this;
    }

    /**
     * Get proActivo
     *
     * @return integer
     */
    public function getProActivo()
    {
        return $this->proActivo;
    }

    /**
     * Set proSucursal
     *
     * @param \BaseBundle\Entity\Sucursal $proSucursal
     *
     * @return Producto
     */
    public function setProSucursal(\BaseBundle\Entity\Sucursal $proSucursal = null)
    {
        $this->proSucursal = $proSucursal;

        return $this;
    }

    /**
     * Get proSucursal
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getProSucursal()
    {
        return $this->proSucursal;
    }
}
