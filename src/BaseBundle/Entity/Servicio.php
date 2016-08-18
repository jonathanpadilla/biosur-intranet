<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicio
 *
 * @ORM\Table(name="servicio")
 * @ORM\Entity
 */
class Servicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ser_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="ser_nombre", type="string", length=100, nullable=true)
     */
    private $serNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="ser_abrebiado", type="string", length=10, nullable=true)
     */
    private $serAbrebiado;

    /**
     * @var integer
     *
     * @ORM\Column(name="ser_precio", type="integer", nullable=true)
     */
    private $serPrecio;

    /**
     * @var string
     *
     * @ORM\Column(name="ser_comentario", type="text", length=65535, nullable=true)
     */
    private $serComentario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ser_fecharegistro", type="datetime", nullable=false)
     */
    private $serFecharegistro;



    /**
     * Get serIdPk
     *
     * @return integer
     */
    public function getSerIdPk()
    {
        return $this->serIdPk;
    }

    /**
     * Set serNombre
     *
     * @param string $serNombre
     *
     * @return Servicio
     */
    public function setSerNombre($serNombre)
    {
        $this->serNombre = $serNombre;

        return $this;
    }

    /**
     * Get serNombre
     *
     * @return string
     */
    public function getSerNombre()
    {
        return $this->serNombre;
    }

    /**
     * Set serAbrebiado
     *
     * @param string $serAbrebiado
     *
     * @return Servicio
     */
    public function setSerAbrebiado($serAbrebiado)
    {
        $this->serAbrebiado = $serAbrebiado;

        return $this;
    }

    /**
     * Get serAbrebiado
     *
     * @return string
     */
    public function getSerAbrebiado()
    {
        return $this->serAbrebiado;
    }

    /**
     * Set serPrecio
     *
     * @param integer $serPrecio
     *
     * @return Servicio
     */
    public function setSerPrecio($serPrecio)
    {
        $this->serPrecio = $serPrecio;

        return $this;
    }

    /**
     * Get serPrecio
     *
     * @return integer
     */
    public function getSerPrecio()
    {
        return $this->serPrecio;
    }

    /**
     * Set serComentario
     *
     * @param string $serComentario
     *
     * @return Servicio
     */
    public function setSerComentario($serComentario)
    {
        $this->serComentario = $serComentario;

        return $this;
    }

    /**
     * Get serComentario
     *
     * @return string
     */
    public function getSerComentario()
    {
        return $this->serComentario;
    }

    /**
     * Set serFecharegistro
     *
     * @param \DateTime $serFecharegistro
     *
     * @return Servicio
     */
    public function setSerFecharegistro($serFecharegistro)
    {
        $this->serFecharegistro = $serFecharegistro;

        return $this;
    }

    /**
     * Get serFecharegistro
     *
     * @return \DateTime
     */
    public function getSerFecharegistro()
    {
        return $this->serFecharegistro;
    }
}
