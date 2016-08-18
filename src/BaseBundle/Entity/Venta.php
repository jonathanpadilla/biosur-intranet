<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Venta
 *
 * @ORM\Table(name="venta", indexes={@ORM\Index(name="ven_sucursal_fk", columns={"ven_sucursal_fk"}), @ORM\Index(name="ven_cliente_fk", columns={"ven_cliente_fk"}), @ORM\Index(name="ven_usuario_fk", columns={"ven_usuario_fk"})})
 * @ORM\Entity
 */
class Venta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ven_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $venIdPk;

    /**
     * @var integer
     *
     * @ORM\Column(name="ven_tipo", type="integer", nullable=true)
     */
    private $venTipo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ven_fechainicio", type="datetime", nullable=true)
     */
    private $venFechainicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ven_fechatermino", type="datetime", nullable=true)
     */
    private $venFechatermino;

    /**
     * @var integer
     *
     * @ORM\Column(name="ven_valor", type="integer", nullable=true)
     */
    private $venValor;

    /**
     * @var integer
     *
     * @ORM\Column(name="ven_diapago", type="integer", nullable=true)
     */
    private $venDiapago;

    /**
     * @var integer
     *
     * @ORM\Column(name="ven_finalizado", type="integer", nullable=true)
     */
    private $venFinalizado;

    /**
     * @var string
     *
     * @ORM\Column(name="ven_comentario", type="text", length=65535, nullable=true)
     */
    private $venComentario;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ven_usuario_fk", referencedColumnName="usu_id_pk")
     * })
     */
    private $venUsuarioFk;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ven_cliente_fk", referencedColumnName="cli_id_pk")
     * })
     */
    private $venClienteFk;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ven_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $venSucursalFk;



    /**
     * Get venIdPk
     *
     * @return integer
     */
    public function getVenIdPk()
    {
        return $this->venIdPk;
    }

    /**
     * Set venTipo
     *
     * @param integer $venTipo
     *
     * @return Venta
     */
    public function setVenTipo($venTipo)
    {
        $this->venTipo = $venTipo;

        return $this;
    }

    /**
     * Get venTipo
     *
     * @return integer
     */
    public function getVenTipo()
    {
        return $this->venTipo;
    }

    /**
     * Set venFechainicio
     *
     * @param \DateTime $venFechainicio
     *
     * @return Venta
     */
    public function setVenFechainicio($venFechainicio)
    {
        $this->venFechainicio = $venFechainicio;

        return $this;
    }

    /**
     * Get venFechainicio
     *
     * @return \DateTime
     */
    public function getVenFechainicio()
    {
        return $this->venFechainicio;
    }

    /**
     * Set venFechatermino
     *
     * @param \DateTime $venFechatermino
     *
     * @return Venta
     */
    public function setVenFechatermino($venFechatermino)
    {
        $this->venFechatermino = $venFechatermino;

        return $this;
    }

    /**
     * Get venFechatermino
     *
     * @return \DateTime
     */
    public function getVenFechatermino()
    {
        return $this->venFechatermino;
    }

    /**
     * Set venValor
     *
     * @param integer $venValor
     *
     * @return Venta
     */
    public function setVenValor($venValor)
    {
        $this->venValor = $venValor;

        return $this;
    }

    /**
     * Get venValor
     *
     * @return integer
     */
    public function getVenValor()
    {
        return $this->venValor;
    }

    /**
     * Set venDiapago
     *
     * @param integer $venDiapago
     *
     * @return Venta
     */
    public function setVenDiapago($venDiapago)
    {
        $this->venDiapago = $venDiapago;

        return $this;
    }

    /**
     * Get venDiapago
     *
     * @return integer
     */
    public function getVenDiapago()
    {
        return $this->venDiapago;
    }

    /**
     * Set venFinalizado
     *
     * @param integer $venFinalizado
     *
     * @return Venta
     */
    public function setVenFinalizado($venFinalizado)
    {
        $this->venFinalizado = $venFinalizado;

        return $this;
    }

    /**
     * Get venFinalizado
     *
     * @return integer
     */
    public function getVenFinalizado()
    {
        return $this->venFinalizado;
    }

    /**
     * Set venComentario
     *
     * @param string $venComentario
     *
     * @return Venta
     */
    public function setVenComentario($venComentario)
    {
        $this->venComentario = $venComentario;

        return $this;
    }

    /**
     * Get venComentario
     *
     * @return string
     */
    public function getVenComentario()
    {
        return $this->venComentario;
    }

    /**
     * Set venUsuarioFk
     *
     * @param \BaseBundle\Entity\Usuario $venUsuarioFk
     *
     * @return Venta
     */
    public function setVenUsuarioFk(\BaseBundle\Entity\Usuario $venUsuarioFk = null)
    {
        $this->venUsuarioFk = $venUsuarioFk;

        return $this;
    }

    /**
     * Get venUsuarioFk
     *
     * @return \BaseBundle\Entity\Usuario
     */
    public function getVenUsuarioFk()
    {
        return $this->venUsuarioFk;
    }

    /**
     * Set venClienteFk
     *
     * @param \BaseBundle\Entity\Cliente $venClienteFk
     *
     * @return Venta
     */
    public function setVenClienteFk(\BaseBundle\Entity\Cliente $venClienteFk = null)
    {
        $this->venClienteFk = $venClienteFk;

        return $this;
    }

    /**
     * Get venClienteFk
     *
     * @return \BaseBundle\Entity\Cliente
     */
    public function getVenClienteFk()
    {
        return $this->venClienteFk;
    }

    /**
     * Set venSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $venSucursalFk
     *
     * @return Venta
     */
    public function setVenSucursalFk(\BaseBundle\Entity\Sucursal $venSucursalFk = null)
    {
        $this->venSucursalFk = $venSucursalFk;

        return $this;
    }

    /**
     * Get venSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getVenSucursalFk()
    {
        return $this->venSucursalFk;
    }
}
