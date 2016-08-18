<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provincia
 *
 * @ORM\Table(name="provincia", indexes={@ORM\Index(name="pro_region_fk", columns={"pro_region_fk"})})
 * @ORM\Entity
 */
class Provincia
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
     * @ORM\Column(name="pro_cantcomunas", type="integer", nullable=true)
     */
    private $proCantcomunas;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pro_region_fk", referencedColumnName="reg_id_pk")
     * })
     */
    private $proRegionFk;



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
     * @return Provincia
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
     * Set proCantcomunas
     *
     * @param integer $proCantcomunas
     *
     * @return Provincia
     */
    public function setProCantcomunas($proCantcomunas)
    {
        $this->proCantcomunas = $proCantcomunas;

        return $this;
    }

    /**
     * Get proCantcomunas
     *
     * @return integer
     */
    public function getProCantcomunas()
    {
        return $this->proCantcomunas;
    }

    /**
     * Set proRegionFk
     *
     * @param \BaseBundle\Entity\Region $proRegionFk
     *
     * @return Provincia
     */
    public function setProRegionFk(\BaseBundle\Entity\Region $proRegionFk = null)
    {
        $this->proRegionFk = $proRegionFk;

        return $this;
    }

    /**
     * Get proRegionFk
     *
     * @return \BaseBundle\Entity\Region
     */
    public function getProRegionFk()
    {
        return $this->proRegionFk;
    }
}
