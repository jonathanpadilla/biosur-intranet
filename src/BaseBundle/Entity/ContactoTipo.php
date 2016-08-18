<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactoTipo
 *
 * @ORM\Table(name="contacto_tipo")
 * @ORM\Entity
 */
class ContactoTipo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cti_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ctiIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="cti_nombre", type="string", length=50, nullable=true)
     */
    private $ctiNombre;



    /**
     * Get ctiIdPk
     *
     * @return integer
     */
    public function getCtiIdPk()
    {
        return $this->ctiIdPk;
    }

    /**
     * Set ctiNombre
     *
     * @param string $ctiNombre
     *
     * @return ContactoTipo
     */
    public function setCtiNombre($ctiNombre)
    {
        $this->ctiNombre = $ctiNombre;

        return $this;
    }

    /**
     * Get ctiNombre
     *
     * @return string
     */
    public function getCtiNombre()
    {
        return $this->ctiNombre;
    }
}
