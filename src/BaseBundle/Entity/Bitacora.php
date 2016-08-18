<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bitacora
 *
 * @ORM\Table(name="bitacora", indexes={@ORM\Index(name="bit_sucursal_fk", columns={"bit_sucursal_fk"}), @ORM\Index(name="bit_venta_fk", columns={"bit_venta_fk"})})
 * @ORM\Entity
 */
class Bitacora
{
    /**
     * @var integer
     *
     * @ORM\Column(name="bit_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $bitIdPk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bit_fecha", type="datetime", nullable=true)
     */
    private $bitFecha;

    /**
     * @var string
     *
     * @ORM\Column(name="bit_descripcion", type="text", length=65535, nullable=true)
     */
    private $bitDescripcion;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bit_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $bitSucursalFk;

    /**
     * @var \Venta
     *
     * @ORM\ManyToOne(targetEntity="Venta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bit_venta_fk", referencedColumnName="ven_id_pk")
     * })
     */
    private $bitVentaFk;



    /**
     * Get bitIdPk
     *
     * @return integer
     */
    public function getBitIdPk()
    {
        return $this->bitIdPk;
    }

    /**
     * Set bitFecha
     *
     * @param \DateTime $bitFecha
     *
     * @return Bitacora
     */
    public function setBitFecha($bitFecha)
    {
        $this->bitFecha = $bitFecha;

        return $this;
    }

    /**
     * Get bitFecha
     *
     * @return \DateTime
     */
    public function getBitFecha()
    {
        return $this->bitFecha;
    }

    /**
     * Set bitDescripcion
     *
     * @param string $bitDescripcion
     *
     * @return Bitacora
     */
    public function setBitDescripcion($bitDescripcion)
    {
        $this->bitDescripcion = $bitDescripcion;

        return $this;
    }

    /**
     * Get bitDescripcion
     *
     * @return string
     */
    public function getBitDescripcion()
    {
        return $this->bitDescripcion;
    }

    /**
     * Set bitSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $bitSucursalFk
     *
     * @return Bitacora
     */
    public function setBitSucursalFk(\BaseBundle\Entity\Sucursal $bitSucursalFk = null)
    {
        $this->bitSucursalFk = $bitSucursalFk;

        return $this;
    }

    /**
     * Get bitSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getBitSucursalFk()
    {
        return $this->bitSucursalFk;
    }

    /**
     * Set bitVentaFk
     *
     * @param \BaseBundle\Entity\Venta $bitVentaFk
     *
     * @return Bitacora
     */
    public function setBitVentaFk(\BaseBundle\Entity\Venta $bitVentaFk = null)
    {
        $this->bitVentaFk = $bitVentaFk;

        return $this;
    }

    /**
     * Get bitVentaFk
     *
     * @return \BaseBundle\Entity\Venta
     */
    public function getBitVentaFk()
    {
        return $this->bitVentaFk;
    }
}
