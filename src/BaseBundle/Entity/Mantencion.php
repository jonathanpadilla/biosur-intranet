<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mantencion
 *
 * @ORM\Table(name="mantencion", indexes={@ORM\Index(name="man_ruta_fk", columns={"man_ruta_fk"}), @ORM\Index(name="man_nnbanno_fk", columns={"man_nnbanno_fk"}), @ORM\Index(name="man_usuario", columns={"man_usuario_fk"}), @ORM\Index(name="man_detallecontrato_fk", columns={"man_detallecontrato_fk"})})
 * @ORM\Entity
 */
class Mantencion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="man_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $manIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="man_foto", type="string", length=200, nullable=true)
     */
    private $manFoto;

    /**
     * @var string
     *
     * @ORM\Column(name="man_lat", type="string", length=50, nullable=true)
     */
    private $manLat;

    /**
     * @var string
     *
     * @ORM\Column(name="man_lng", type="string", length=50, nullable=true)
     */
    private $manLng;

    /**
     * @var string
     *
     * @ORM\Column(name="man_comentario", type="text", length=65535, nullable=true)
     */
    private $manComentario;

    /**
     * @var integer
     *
     * @ORM\Column(name="man_realizado", type="integer", nullable=true)
     */
    private $manRealizado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="man_fecharegistro", type="datetime", nullable=true)
     */
    private $manFecharegistro;

    /**
     * @var \Ruta
     *
     * @ORM\ManyToOne(targetEntity="Ruta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="man_ruta_fk", referencedColumnName="rut_id_pk")
     * })
     */
    private $manRutaFk;

    /**
     * @var \DetcontratoNnBanno
     *
     * @ORM\ManyToOne(targetEntity="DetcontratoNnBanno")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="man_nnbanno_fk", referencedColumnName="dnnb_id_pk")
     * })
     */
    private $manNnbannoFk;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="man_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $manUsuarioFk;

    /**
     * @var \DetalleContrato
     *
     * @ORM\ManyToOne(targetEntity="DetalleContrato")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="man_detallecontrato_fk", referencedColumnName="dco_id_pk")
     * })
     */
    private $manDetallecontratoFk;



    /**
     * Get manIdPk
     *
     * @return integer
     */
    public function getManIdPk()
    {
        return $this->manIdPk;
    }

    /**
     * Set manFoto
     *
     * @param string $manFoto
     *
     * @return Mantencion
     */
    public function setManFoto($manFoto)
    {
        $this->manFoto = $manFoto;

        return $this;
    }

    /**
     * Get manFoto
     *
     * @return string
     */
    public function getManFoto()
    {
        return $this->manFoto;
    }

    /**
     * Set manLat
     *
     * @param string $manLat
     *
     * @return Mantencion
     */
    public function setManLat($manLat)
    {
        $this->manLat = $manLat;

        return $this;
    }

    /**
     * Get manLat
     *
     * @return string
     */
    public function getManLat()
    {
        return $this->manLat;
    }

    /**
     * Set manLng
     *
     * @param string $manLng
     *
     * @return Mantencion
     */
    public function setManLng($manLng)
    {
        $this->manLng = $manLng;

        return $this;
    }

    /**
     * Get manLng
     *
     * @return string
     */
    public function getManLng()
    {
        return $this->manLng;
    }

    /**
     * Set manComentario
     *
     * @param string $manComentario
     *
     * @return Mantencion
     */
    public function setManComentario($manComentario)
    {
        $this->manComentario = $manComentario;

        return $this;
    }

    /**
     * Get manComentario
     *
     * @return string
     */
    public function getManComentario()
    {
        return $this->manComentario;
    }

    /**
     * Set manRealizado
     *
     * @param integer $manRealizado
     *
     * @return Mantencion
     */
    public function setManRealizado($manRealizado)
    {
        $this->manRealizado = $manRealizado;

        return $this;
    }

    /**
     * Get manRealizado
     *
     * @return integer
     */
    public function getManRealizado()
    {
        return $this->manRealizado;
    }

    /**
     * Set manFecharegistro
     *
     * @param \DateTime $manFecharegistro
     *
     * @return Mantencion
     */
    public function setManFecharegistro($manFecharegistro)
    {
        $this->manFecharegistro = $manFecharegistro;

        return $this;
    }

    /**
     * Get manFecharegistro
     *
     * @return \DateTime
     */
    public function getManFecharegistro()
    {
        return $this->manFecharegistro;
    }

    /**
     * Set manRutaFk
     *
     * @param \BaseBundle\Entity\Ruta $manRutaFk
     *
     * @return Mantencion
     */
    public function setManRutaFk(\BaseBundle\Entity\Ruta $manRutaFk = null)
    {
        $this->manRutaFk = $manRutaFk;

        return $this;
    }

    /**
     * Get manRutaFk
     *
     * @return \BaseBundle\Entity\Ruta
     */
    public function getManRutaFk()
    {
        return $this->manRutaFk;
    }

    /**
     * Set manNnbannoFk
     *
     * @param \BaseBundle\Entity\DetcontratoNnBanno $manNnbannoFk
     *
     * @return Mantencion
     */
    public function setManNnbannoFk(\BaseBundle\Entity\DetcontratoNnBanno $manNnbannoFk = null)
    {
        $this->manNnbannoFk = $manNnbannoFk;

        return $this;
    }

    /**
     * Get manNnbannoFk
     *
     * @return \BaseBundle\Entity\DetcontratoNnBanno
     */
    public function getManNnbannoFk()
    {
        return $this->manNnbannoFk;
    }

    /**
     * Set manUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $manUsuarioFk
     *
     * @return Mantencion
     */
    public function setManUsuarioFk(\BaseBundle\Entity\Usuario $manUsuarioFk = null)
    {
        $this->manUsuarioFk = $manUsuarioFk;

        return $this;
    }

    /**
     * Get manUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getManUsuarioFk()
    {
        return $this->manUsuarioFk;
    }

    /**
     * Set manDetallecontratoFk
     *
     * @param \BaseBundle\Entity\DetalleContrato $manDetallecontratoFk
     *
     * @return Mantencion
     */
    public function setManDetallecontratoFk(\BaseBundle\Entity\DetalleContrato $manDetallecontratoFk = null)
    {
        $this->manDetallecontratoFk = $manDetallecontratoFk;

        return $this;
    }

    /**
     * Get manDetallecontratoFk
     *
     * @return \BaseBundle\Entity\DetalleContrato
     */
    public function getManDetallecontratoFk()
    {
        return $this->manDetallecontratoFk;
    }
}
