<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Camion
 *
 * @ORM\Table(name="camion", indexes={@ORM\Index(name="cam_usuario_fk", columns={"cam_usuario_fk"}), @ORM\Index(name="cam_sucursal_fk", columns={"cam_sucursal_fk"})})
 * @ORM\Entity
 */
class Camion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cam_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $camIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="cam_patente", type="string", length=20, nullable=true)
     */
    private $camPatente;

    /**
     * @var integer
     *
     * @ORM\Column(name="cam_activo", type="integer", nullable=true)
     */
    private $camActivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cam_fecharegistro", type="datetime", nullable=true)
     */
    private $camFecharegistro;

    /**
     * @var string
     *
     * @ORM\Column(name="cam_comentario", type="text", length=65535, nullable=true)
     */
    private $camComentario;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cam_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $camUsuarioFk;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cam_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $camSucursalFk;



    /**
     * Get camIdPk
     *
     * @return integer
     */
    public function getCamIdPk()
    {
        return $this->camIdPk;
    }

    /**
     * Set camPatente
     *
     * @param string $camPatente
     *
     * @return Camion
     */
    public function setCamPatente($camPatente)
    {
        $this->camPatente = $camPatente;

        return $this;
    }

    /**
     * Get camPatente
     *
     * @return string
     */
    public function getCamPatente()
    {
        return $this->camPatente;
    }

    /**
     * Set camActivo
     *
     * @param integer $camActivo
     *
     * @return Camion
     */
    public function setCamActivo($camActivo)
    {
        $this->camActivo = $camActivo;

        return $this;
    }

    /**
     * Get camActivo
     *
     * @return integer
     */
    public function getCamActivo()
    {
        return $this->camActivo;
    }

    /**
     * Set camFecharegistro
     *
     * @param \DateTime $camFecharegistro
     *
     * @return Camion
     */
    public function setCamFecharegistro($camFecharegistro)
    {
        $this->camFecharegistro = $camFecharegistro;

        return $this;
    }

    /**
     * Get camFecharegistro
     *
     * @return \DateTime
     */
    public function getCamFecharegistro()
    {
        return $this->camFecharegistro;
    }

    /**
     * Set camComentario
     *
     * @param string $camComentario
     *
     * @return Camion
     */
    public function setCamComentario($camComentario)
    {
        $this->camComentario = $camComentario;

        return $this;
    }

    /**
     * Get camComentario
     *
     * @return string
     */
    public function getCamComentario()
    {
        return $this->camComentario;
    }

    /**
     * Set camUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $camUsuarioFk
     *
     * @return Camion
     */
    public function setCamUsuarioFk(\BaseBundle\Entity\Usuario $camUsuarioFk = null)
    {
        $this->camUsuarioFk = $camUsuarioFk;

        return $this;
    }

    /**
     * Get camUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getCamUsuarioFk()
    {
        return $this->camUsuarioFk;
    }

    /**
     * Set camSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $camSucursalFk
     *
     * @return Camion
     */
    public function setCamSucursalFk(\BaseBundle\Entity\Sucursal $camSucursalFk = null)
    {
        $this->camSucursalFk = $camSucursalFk;

        return $this;
    }

    /**
     * Get camSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getCamSucursalFk()
    {
        return $this->camSucursalFk;
    }
}
