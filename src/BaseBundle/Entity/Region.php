<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity
 */
class Region
{
    /**
     * @var integer
     *
     * @ORM\Column(name="reg_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $regIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="reg_nombre", type="string", length=100, nullable=true)
     */
    private $regNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="reg_romano", type="string", length=10, nullable=true)
     */
    private $regRomano;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_cantprovincias", type="integer", nullable=true)
     */
    private $regCantprovincias;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_cantcomunas", type="integer", nullable=true)
     */
    private $regCantcomunas;



    /**
     * Get regIdPk
     *
     * @return integer
     */
    public function getRegIdPk()
    {
        return $this->regIdPk;
    }

    /**
     * Set regNombre
     *
     * @param string $regNombre
     *
     * @return Region
     */
    public function setRegNombre($regNombre)
    {
        $this->regNombre = $regNombre;

        return $this;
    }

    /**
     * Get regNombre
     *
     * @return string
     */
    public function getRegNombre()
    {
        return $this->regNombre;
    }

    /**
     * Set regRomano
     *
     * @param string $regRomano
     *
     * @return Region
     */
    public function setRegRomano($regRomano)
    {
        $this->regRomano = $regRomano;

        return $this;
    }

    /**
     * Get regRomano
     *
     * @return string
     */
    public function getRegRomano()
    {
        return $this->regRomano;
    }

    /**
     * Set regCantprovincias
     *
     * @param integer $regCantprovincias
     *
     * @return Region
     */
    public function setRegCantprovincias($regCantprovincias)
    {
        $this->regCantprovincias = $regCantprovincias;

        return $this;
    }

    /**
     * Get regCantprovincias
     *
     * @return integer
     */
    public function getRegCantprovincias()
    {
        return $this->regCantprovincias;
    }

    /**
     * Set regCantcomunas
     *
     * @param integer $regCantcomunas
     *
     * @return Region
     */
    public function setRegCantcomunas($regCantcomunas)
    {
        $this->regCantcomunas = $regCantcomunas;

        return $this;
    }

    /**
     * Get regCantcomunas
     *
     * @return integer
     */
    public function getRegCantcomunas()
    {
        return $this->regCantcomunas;
    }
}
