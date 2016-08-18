<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetcontratoNnBanno
 *
 * @ORM\Table(name="detcontrato_nn_banno", indexes={@ORM\Index(name="dnnb_detcontrato_fk", columns={"dnnb_detcontrato_fk"}), @ORM\Index(name="dnnb_banno_fk", columns={"dnnb_banno_fk"}), @ORM\Index(name="dnnb_lavamano_fk", columns={"dnnb_lavamano_fk"})})
 * @ORM\Entity
 */
class DetcontratoNnBanno
{
    /**
     * @var integer
     *
     * @ORM\Column(name="dnnb_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dnnbIdPk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dnnb_fecharegistro", type="datetime", nullable=true)
     */
    private $dnnbFecharegistro;

    /**
     * @var integer
     *
     * @ORM\Column(name="dnnb_candado", type="integer", nullable=true)
     */
    private $dnnbCandado;

    /**
     * @var integer
     *
     * @ORM\Column(name="dnnb_activo", type="integer", nullable=true)
     */
    private $dnnbActivo;

    /**
     * @var \Bannos
     *
     * @ORM\ManyToOne(targetEntity="Bannos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dnnb_banno_fk", referencedColumnName="ban_id_pk")
     * })
     */
    private $dnnbBannoFk;

    /**
     * @var \DetalleContrato
     *
     * @ORM\ManyToOne(targetEntity="DetalleContrato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dnnb_detcontrato_fk", referencedColumnName="dco_id_pk")
     * })
     */
    private $dnnbDetcontratoFk;

    /**
     * @var \Bannos
     *
     * @ORM\ManyToOne(targetEntity="Bannos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dnnb_lavamano_fk", referencedColumnName="ban_id_pk")
     * })
     */
    private $dnnbLavamanoFk;



    /**
     * Get dnnbIdPk
     *
     * @return integer
     */
    public function getDnnbIdPk()
    {
        return $this->dnnbIdPk;
    }

    /**
     * Set dnnbFecharegistro
     *
     * @param \DateTime $dnnbFecharegistro
     *
     * @return DetcontratoNnBanno
     */
    public function setDnnbFecharegistro($dnnbFecharegistro)
    {
        $this->dnnbFecharegistro = $dnnbFecharegistro;

        return $this;
    }

    /**
     * Get dnnbFecharegistro
     *
     * @return \DateTime
     */
    public function getDnnbFecharegistro()
    {
        return $this->dnnbFecharegistro;
    }

    /**
     * Set dnnbCandado
     *
     * @param integer $dnnbCandado
     *
     * @return DetcontratoNnBanno
     */
    public function setDnnbCandado($dnnbCandado)
    {
        $this->dnnbCandado = $dnnbCandado;

        return $this;
    }

    /**
     * Get dnnbCandado
     *
     * @return integer
     */
    public function getDnnbCandado()
    {
        return $this->dnnbCandado;
    }

    /**
     * Set dnnbActivo
     *
     * @param integer $dnnbActivo
     *
     * @return DetcontratoNnBanno
     */
    public function setDnnbActivo($dnnbActivo)
    {
        $this->dnnbActivo = $dnnbActivo;

        return $this;
    }

    /**
     * Get dnnbActivo
     *
     * @return integer
     */
    public function getDnnbActivo()
    {
        return $this->dnnbActivo;
    }

    /**
     * Set dnnbBannoFk
     *
     * @param \BaseBundle\Entity\Bannos $dnnbBannoFk
     *
     * @return DetcontratoNnBanno
     */
    public function setDnnbBannoFk(\BaseBundle\Entity\Bannos $dnnbBannoFk = null)
    {
        $this->dnnbBannoFk = $dnnbBannoFk;

        return $this;
    }

    /**
     * Get dnnbBannoFk
     *
     * @return \BaseBundle\Entity\Bannos
     */
    public function getDnnbBannoFk()
    {
        return $this->dnnbBannoFk;
    }

    /**
     * Set dnnbDetcontratoFk
     *
     * @param \BaseBundle\Entity\DetalleContrato $dnnbDetcontratoFk
     *
     * @return DetcontratoNnBanno
     */
    public function setDnnbDetcontratoFk(\BaseBundle\Entity\DetalleContrato $dnnbDetcontratoFk = null)
    {
        $this->dnnbDetcontratoFk = $dnnbDetcontratoFk;

        return $this;
    }

    /**
     * Get dnnbDetcontratoFk
     *
     * @return \BaseBundle\Entity\DetalleContrato
     */
    public function getDnnbDetcontratoFk()
    {
        return $this->dnnbDetcontratoFk;
    }

    /**
     * Set dnnbLavamanoFk
     *
     * @param \BaseBundle\Entity\Bannos $dnnbLavamanoFk
     *
     * @return DetcontratoNnBanno
     */
    public function setDnnbLavamanoFk(\BaseBundle\Entity\Bannos $dnnbLavamanoFk = null)
    {
        $this->dnnbLavamanoFk = $dnnbLavamanoFk;

        return $this;
    }

    /**
     * Get dnnbLavamanoFk
     *
     * @return \BaseBundle\Entity\Bannos
     */
    public function getDnnbLavamanoFk()
    {
        return $this->dnnbLavamanoFk;
    }
}
