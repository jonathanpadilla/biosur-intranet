<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ruta
 *
 * @ORM\Table(name="ruta", indexes={@ORM\Index(name="man_camion_fk", columns={"rut_camion_fk"}), @ORM\Index(name="man_detallecontrato_fk", columns={"rut_detallecontrato_fk"})})
 * @ORM\Entity
 */
class Ruta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rut_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rutIdPk;

    /**
     * @var integer
     *
     * @ORM\Column(name="rut_dia", type="integer", nullable=true)
     */
    private $rutDia;

    /**
     * @var integer
     *
     * @ORM\Column(name="rut_solicitado", type="integer", nullable=true)
     */
    private $rutSolicitado;

    /**
     * @var integer
     *
     * @ORM\Column(name="rut_activo", type="integer", nullable=true)
     */
    private $rutActivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rut_fecharegistro", type="datetime", nullable=true)
     */
    private $rutFecharegistro;

    /**
     * @var integer
     *
     * @ORM\Column(name="rut_orden", type="integer", nullable=false)
     */
    private $rutOrden = '0';

    /**
     * @var \DetalleContrato
     *
     * @ORM\ManyToOne(targetEntity="DetalleContrato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rut_detallecontrato_fk", referencedColumnName="dco_id_pk")
     * })
     */
    private $rutDetallecontratoFk;

    /**
     * @var \Camion
     *
     * @ORM\ManyToOne(targetEntity="Camion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rut_camion_fk", referencedColumnName="cam_id_pk")
     * })
     */
    private $rutCamionFk;



    /**
     * Get rutIdPk
     *
     * @return integer
     */
    public function getRutIdPk()
    {
        return $this->rutIdPk;
    }

    /**
     * Set rutDia
     *
     * @param integer $rutDia
     *
     * @return Ruta
     */
    public function setRutDia($rutDia)
    {
        $this->rutDia = $rutDia;

        return $this;
    }

    /**
     * Get rutDia
     *
     * @return integer
     */
    public function getRutDia()
    {
        return $this->rutDia;
    }

    /**
     * Set rutSolicitado
     *
     * @param integer $rutSolicitado
     *
     * @return Ruta
     */
    public function setRutSolicitado($rutSolicitado)
    {
        $this->rutSolicitado = $rutSolicitado;

        return $this;
    }

    /**
     * Get rutSolicitado
     *
     * @return integer
     */
    public function getRutSolicitado()
    {
        return $this->rutSolicitado;
    }

    /**
     * Set rutActivo
     *
     * @param integer $rutActivo
     *
     * @return Ruta
     */
    public function setRutActivo($rutActivo)
    {
        $this->rutActivo = $rutActivo;

        return $this;
    }

    /**
     * Get rutActivo
     *
     * @return integer
     */
    public function getRutActivo()
    {
        return $this->rutActivo;
    }

    /**
     * Set rutFecharegistro
     *
     * @param \DateTime $rutFecharegistro
     *
     * @return Ruta
     */
    public function setRutFecharegistro($rutFecharegistro)
    {
        $this->rutFecharegistro = $rutFecharegistro;

        return $this;
    }

    /**
     * Get rutFecharegistro
     *
     * @return \DateTime
     */
    public function getRutFecharegistro()
    {
        return $this->rutFecharegistro;
    }

    /**
     * Set rutOrden
     *
     * @param integer $rutOrden
     *
     * @return Ruta
     */
    public function setRutOrden($rutOrden)
    {
        $this->rutOrden = $rutOrden;

        return $this;
    }

    /**
     * Get rutOrden
     *
     * @return integer
     */
    public function getRutOrden()
    {
        return $this->rutOrden;
    }

    /**
     * Set rutDetallecontratoFk
     *
     * @param \BaseBundle\Entity\DetalleContrato $rutDetallecontratoFk
     *
     * @return Ruta
     */
    public function setRutDetallecontratoFk(\BaseBundle\Entity\DetalleContrato $rutDetallecontratoFk = null)
    {
        $this->rutDetallecontratoFk = $rutDetallecontratoFk;

        return $this;
    }

    /**
     * Get rutDetallecontratoFk
     *
     * @return \BaseBundle\Entity\DetalleContrato
     */
    public function getRutDetallecontratoFk()
    {
        return $this->rutDetallecontratoFk;
    }

    /**
     * Set rutCamionFk
     *
     * @param \BaseBundle\Entity\Camion $rutCamionFk
     *
     * @return Ruta
     */
    public function setRutCamionFk(\BaseBundle\Entity\Camion $rutCamionFk = null)
    {
        $this->rutCamionFk = $rutCamionFk;

        return $this;
    }

    /**
     * Get rutCamionFk
     *
     * @return \BaseBundle\Entity\Camion
     */
    public function getRutCamionFk()
    {
        return $this->rutCamionFk;
    }
}
