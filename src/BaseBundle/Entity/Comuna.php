<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comuna
 *
 * @ORM\Table(name="comuna", indexes={@ORM\Index(name="com_provincia_fk", columns={"com_provincia_fk"})})
 * @ORM\Entity
 */
class Comuna
{
    /**
     * @var integer
     *
     * @ORM\Column(name="com_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $comIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="com_nombre", type="string", length=100, nullable=true)
     */
    private $comNombre;

    /**
     * @var \Provincia
     *
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com_provincia_fk", referencedColumnName="pro_id_pk")
     * })
     */
    private $comProvinciaFk;



    /**
     * Get comIdPk
     *
     * @return integer
     */
    public function getComIdPk()
    {
        return $this->comIdPk;
    }

    /**
     * Set comNombre
     *
     * @param string $comNombre
     *
     * @return Comuna
     */
    public function setComNombre($comNombre)
    {
        $this->comNombre = $comNombre;

        return $this;
    }

    /**
     * Get comNombre
     *
     * @return string
     */
    public function getComNombre()
    {
        return $this->comNombre;
    }

    /**
     * Set comProvinciaFk
     *
     * @param \BaseBundle\Entity\Provincia $comProvinciaFk
     *
     * @return Comuna
     */
    public function setComProvinciaFk(\BaseBundle\Entity\Provincia $comProvinciaFk = null)
    {
        $this->comProvinciaFk = $comProvinciaFk;

        return $this;
    }

    /**
     * Get comProvinciaFk
     *
     * @return \BaseBundle\Entity\Provincia
     */
    public function getComProvinciaFk()
    {
        return $this->comProvinciaFk;
    }
}
