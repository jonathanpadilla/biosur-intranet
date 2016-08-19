<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleContrato
 *
 * @ORM\Table(name="detalle_contrato", indexes={@ORM\Index(name="dco_venta_fk", columns={"dco_venta_fk"}), @ORM\Index(name="dco_comuna_fk", columns={"dco_comuna_fk"}), @ORM\Index(name="dco_servicio_fk", columns={"dco_servicio_fk"})})
 * @ORM\Entity
 */
class DetalleContrato
{
    /**
     * @var integer
     *
     * @ORM\Column(name="dco_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dcoIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="dco_direccion", type="string", length=100, nullable=true)
     */
    private $dcoDireccion;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_cbano", type="integer", nullable=true)
     */
    private $dcoCbano;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_ccaseta", type="integer", nullable=true)
     */
    private $dcoCcaseta;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_cducha", type="integer", nullable=true)
     */
    private $dcoCducha;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_cexterno", type="integer", nullable=true)
     */
    private $dcoCexterno;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_clavamano", type="integer", nullable=true)
     */
    private $dcoClavamano;

    /**
     * @var string
     *
     * @ORM\Column(name="dco_lat", type="string", length=100, nullable=true)
     */
    private $dcoLat;

    /**
     * @var string
     *
     * @ORM\Column(name="dco_lon", type="string", length=100, nullable=true)
     */
    private $dcoLon;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_papel", type="integer", nullable=true)
     */
    private $dcoPapel;

    /**
     * @var integer
     *
     * @ORM\Column(name="dco_sachet", type="integer", nullable=true)
     */
    private $dcoSachet;

    /**
     * @var string
     *
     * @ORM\Column(name="dco_comentario", type="text", length=65535, nullable=true)
     */
    private $dcoComentario;

    /**
     * @var \Venta
     *
     * @ORM\ManyToOne(targetEntity="Venta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dco_venta_fk", referencedColumnName="ven_id_pk")
     * })
     */
    private $dcoVentaFk;

    /**
     * @var \Comuna
     *
     * @ORM\ManyToOne(targetEntity="Comuna")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dco_comuna_fk", referencedColumnName="com_id_pk")
     * })
     */
    private $dcoComunaFk;

    /**
     * @var \Servicio
     *
     * @ORM\ManyToOne(targetEntity="Servicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dco_servicio_fk", referencedColumnName="ser_id_pk")
     * })
     */
    private $dcoServicioFk;



    /**
     * Get dcoIdPk
     *
     * @return integer
     */
    public function getDcoIdPk()
    {
        return $this->dcoIdPk;
    }

    /**
     * Set dcoDireccion
     *
     * @param string $dcoDireccion
     *
     * @return DetalleContrato
     */
    public function setDcoDireccion($dcoDireccion)
    {
        $this->dcoDireccion = $dcoDireccion;

        return $this;
    }

    /**
     * Get dcoDireccion
     *
     * @return string
     */
    public function getDcoDireccion()
    {
        return $this->dcoDireccion;
    }

    /**
     * Set dcoCbano
     *
     * @param integer $dcoCbano
     *
     * @return DetalleContrato
     */
    public function setDcoCbano($dcoCbano)
    {
        $this->dcoCbano = $dcoCbano;

        return $this;
    }

    /**
     * Get dcoCbano
     *
     * @return integer
     */
    public function getDcoCbano()
    {
        return $this->dcoCbano;
    }

    /**
     * Set dcoCcaseta
     *
     * @param integer $dcoCcaseta
     *
     * @return DetalleContrato
     */
    public function setDcoCcaseta($dcoCcaseta)
    {
        $this->dcoCcaseta = $dcoCcaseta;

        return $this;
    }

    /**
     * Get dcoCcaseta
     *
     * @return integer
     */
    public function getDcoCcaseta()
    {
        return $this->dcoCcaseta;
    }

    /**
     * Set dcoCducha
     *
     * @param integer $dcoCducha
     *
     * @return DetalleContrato
     */
    public function setDcoCducha($dcoCducha)
    {
        $this->dcoCducha = $dcoCducha;

        return $this;
    }

    /**
     * Get dcoCducha
     *
     * @return integer
     */
    public function getDcoCducha()
    {
        return $this->dcoCducha;
    }

    /**
     * Set dcoCexterno
     *
     * @param integer $dcoCexterno
     *
     * @return DetalleContrato
     */
    public function setDcoCexterno($dcoCexterno)
    {
        $this->dcoCexterno = $dcoCexterno;

        return $this;
    }

    /**
     * Get dcoCexterno
     *
     * @return integer
     */
    public function getDcoCexterno()
    {
        return $this->dcoCexterno;
    }

    /**
     * Set dcoClavamano
     *
     * @param integer $dcoClavamano
     *
     * @return DetalleContrato
     */
    public function setDcoClavamano($dcoClavamano)
    {
        $this->dcoClavamano = $dcoClavamano;

        return $this;
    }

    /**
     * Get dcoClavamano
     *
     * @return integer
     */
    public function getDcoClavamano()
    {
        return $this->dcoClavamano;
    }

    /**
     * Set dcoLat
     *
     * @param string $dcoLat
     *
     * @return DetalleContrato
     */
    public function setDcoLat($dcoLat)
    {
        $this->dcoLat = $dcoLat;

        return $this;
    }

    /**
     * Get dcoLat
     *
     * @return string
     */
    public function getDcoLat()
    {
        return $this->dcoLat;
    }

    /**
     * Set dcoLon
     *
     * @param string $dcoLon
     *
     * @return DetalleContrato
     */
    public function setDcoLon($dcoLon)
    {
        $this->dcoLon = $dcoLon;

        return $this;
    }

    /**
     * Get dcoLon
     *
     * @return string
     */
    public function getDcoLon()
    {
        return $this->dcoLon;
    }

    /**
     * Set dcoPapel
     *
     * @param integer $dcoPapel
     *
     * @return DetalleContrato
     */
    public function setDcoPapel($dcoPapel)
    {
        $this->dcoPapel = $dcoPapel;

        return $this;
    }

    /**
     * Get dcoPapel
     *
     * @return integer
     */
    public function getDcoPapel()
    {
        return $this->dcoPapel;
    }

    /**
     * Set dcoSachet
     *
     * @param integer $dcoSachet
     *
     * @return DetalleContrato
     */
    public function setDcoSachet($dcoSachet)
    {
        $this->dcoSachet = $dcoSachet;

        return $this;
    }

    /**
     * Get dcoSachet
     *
     * @return integer
     */
    public function getDcoSachet()
    {
        return $this->dcoSachet;
    }

    /**
     * Set dcoComentario
     *
     * @param string $dcoComentario
     *
     * @return DetalleContrato
     */
    public function setDcoComentario($dcoComentario)
    {
        $this->dcoComentario = $dcoComentario;

        return $this;
    }

    /**
     * Get dcoComentario
     *
     * @return string
     */
    public function getDcoComentario()
    {
        return $this->dcoComentario;
    }

    /**
     * Set dcoVentaFk
     *
     * @param \BaseBundle\Entity\Venta $dcoVentaFk
     *
     * @return DetalleContrato
     */
    public function setDcoVentaFk(\BaseBundle\Entity\Venta $dcoVentaFk = null)
    {
        $this->dcoVentaFk = $dcoVentaFk;

        return $this;
    }

    /**
     * Get dcoVentaFk
     *
     * @return \BaseBundle\Entity\Venta
     */
    public function getDcoVentaFk()
    {
        return $this->dcoVentaFk;
    }

    /**
     * Set dcoComunaFk
     *
     * @param \BaseBundle\Entity\Comuna $dcoComunaFk
     *
     * @return DetalleContrato
     */
    public function setDcoComunaFk(\BaseBundle\Entity\Comuna $dcoComunaFk = null)
    {
        $this->dcoComunaFk = $dcoComunaFk;

        return $this;
    }

    /**
     * Get dcoComunaFk
     *
     * @return \BaseBundle\Entity\Comuna
     */
    public function getDcoComunaFk()
    {
        return $this->dcoComunaFk;
    }

    /**
     * Set dcoServicioFk
     *
     * @param \BaseBundle\Entity\Servicio $dcoServicioFk
     *
     * @return DetalleContrato
     */
    public function setDcoServicioFk(\BaseBundle\Entity\Servicio $dcoServicioFk = null)
    {
        $this->dcoServicioFk = $dcoServicioFk;

        return $this;
    }

    /**
     * Get dcoServicioFk
     *
     * @return \BaseBundle\Entity\Servicio
     */
    public function getDcoServicioFk()
    {
        return $this->dcoServicioFk;
    }
}
