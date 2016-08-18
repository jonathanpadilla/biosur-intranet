<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductoMovimiento
 *
 * @ORM\Table(name="producto_movimiento", indexes={@ORM\Index(name="pmo_sucursal_fk", columns={"pmo_sucursal_fk"}), @ORM\Index(name="pmo_usuario_fk", columns={"pmo_usuario_fk"}), @ORM\Index(name="pmo_producto_fk", columns={"pmo_producto_fk"})})
 * @ORM\Entity
 */
class ProductoMovimiento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="pmo_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pmoIdPk;

    /**
     * @var integer
     *
     * @ORM\Column(name="pmo_tipo", type="integer", nullable=true)
     */
    private $pmoTipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="pmo_cantidad", type="integer", nullable=true)
     */
    private $pmoCantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="pmo_detalle", type="string", length=200, nullable=true)
     */
    private $pmoDetalle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pmo_fecha", type="datetime", nullable=true)
     */
    private $pmoFecha;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pmo_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $pmoSucursalFk;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pmo_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $pmoUsuarioFk;

    /**
     * @var \Producto
     *
     * @ORM\ManyToOne(targetEntity="Producto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pmo_producto_fk", referencedColumnName="pro_id_pk")
     * })
     */
    private $pmoProductoFk;



    /**
     * Get pmoIdPk
     *
     * @return integer
     */
    public function getPmoIdPk()
    {
        return $this->pmoIdPk;
    }

    /**
     * Set pmoTipo
     *
     * @param integer $pmoTipo
     *
     * @return ProductoMovimiento
     */
    public function setPmoTipo($pmoTipo)
    {
        $this->pmoTipo = $pmoTipo;

        return $this;
    }

    /**
     * Get pmoTipo
     *
     * @return integer
     */
    public function getPmoTipo()
    {
        return $this->pmoTipo;
    }

    /**
     * Set pmoCantidad
     *
     * @param integer $pmoCantidad
     *
     * @return ProductoMovimiento
     */
    public function setPmoCantidad($pmoCantidad)
    {
        $this->pmoCantidad = $pmoCantidad;

        return $this;
    }

    /**
     * Get pmoCantidad
     *
     * @return integer
     */
    public function getPmoCantidad()
    {
        return $this->pmoCantidad;
    }

    /**
     * Set pmoDetalle
     *
     * @param string $pmoDetalle
     *
     * @return ProductoMovimiento
     */
    public function setPmoDetalle($pmoDetalle)
    {
        $this->pmoDetalle = $pmoDetalle;

        return $this;
    }

    /**
     * Get pmoDetalle
     *
     * @return string
     */
    public function getPmoDetalle()
    {
        return $this->pmoDetalle;
    }

    /**
     * Set pmoFecha
     *
     * @param \DateTime $pmoFecha
     *
     * @return ProductoMovimiento
     */
    public function setPmoFecha($pmoFecha)
    {
        $this->pmoFecha = $pmoFecha;

        return $this;
    }

    /**
     * Get pmoFecha
     *
     * @return \DateTime
     */
    public function getPmoFecha()
    {
        return $this->pmoFecha;
    }

    /**
     * Set pmoSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $pmoSucursalFk
     *
     * @return ProductoMovimiento
     */
    public function setPmoSucursalFk(\BaseBundle\Entity\Sucursal $pmoSucursalFk = null)
    {
        $this->pmoSucursalFk = $pmoSucursalFk;

        return $this;
    }

    /**
     * Get pmoSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getPmoSucursalFk()
    {
        return $this->pmoSucursalFk;
    }

    /**
     * Set pmoUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $pmoUsuarioFk
     *
     * @return ProductoMovimiento
     */
    public function setPmoUsuarioFk(\BaseBundle\Entity\Usuario $pmoUsuarioFk = null)
    {
        $this->pmoUsuarioFk = $pmoUsuarioFk;

        return $this;
    }

    /**
     * Get pmoUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getPmoUsuarioFk()
    {
        return $this->pmoUsuarioFk;
    }

    /**
     * Set pmoProductoFk
     *
     * @param \BaseBundle\Entity\Producto $pmoProductoFk
     *
     * @return ProductoMovimiento
     */
    public function setPmoProductoFk(\BaseBundle\Entity\Producto $pmoProductoFk = null)
    {
        $this->pmoProductoFk = $pmoProductoFk;

        return $this;
    }

    /**
     * Get pmoProductoFk
     *
     * @return \BaseBundle\Entity\Producto
     */
    public function getPmoProductoFk()
    {
        return $this->pmoProductoFk;
    }
}
