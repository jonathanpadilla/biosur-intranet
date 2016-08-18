<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bannos
 *
 * @ORM\Table(name="bannos", indexes={@ORM\Index(name="ban_sucursal_fk", columns={"ban_sucursal_fk"}), @ORM\Index(name="ban_tipo_id", columns={"ban_tipo_id"}), @ORM\Index(name="ban_cliente_fk", columns={"ban_cliente_fk"})})
 * @ORM\Entity
 */
class Bannos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ban_id_pk", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $banIdPk;

    /**
     * @var string
     *
     * @ORM\Column(name="ban_key", type="string", length=200, nullable=true)
     */
    private $banKey;

    /**
     * @var string
     *
     * @ORM\Column(name="ban_tipo", type="string", length=2, nullable=true)
     */
    private $banTipo;

    /**
     * @var string
     *
     * @ORM\Column(name="ban_marca", type="string", length=100, nullable=true)
     */
    private $banMarca;

    /**
     * @var string
     *
     * @ORM\Column(name="ban_modelo", type="string", length=100, nullable=true)
     */
    private $banModelo;

    /**
     * @var integer
     *
     * @ORM\Column(name="ban_asignado", type="integer", nullable=true)
     */
    private $banAsignado;

    /**
     * @var string
     *
     * @ORM\Column(name="ban_comentario", type="text", length=65535, nullable=true)
     */
    private $banComentario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ban_fecharegistro", type="datetime", nullable=true)
     */
    private $banFecharegistro;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ban_sucursal_fk", referencedColumnName="suc_id_pk")
     * })
     */
    private $banSucursalFk;

    /**
     * @var \BannosTipo
     *
     * @ORM\ManyToOne(targetEntity="BannosTipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ban_tipo_id", referencedColumnName="bti_id_pk")
     * })
     */
    private $banTipo2;

    /**
     * @var \Cliente
     *
     * @ORM\ManyToOne(targetEntity="Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ban_cliente_fk", referencedColumnName="cli_id_pk")
     * })
     */
    private $banClienteFk;



    /**
     * Get banIdPk
     *
     * @return integer
     */
    public function getBanIdPk()
    {
        return $this->banIdPk;
    }

    /**
     * Set banKey
     *
     * @param string $banKey
     *
     * @return Bannos
     */
    public function setBanKey($banKey)
    {
        $this->banKey = $banKey;

        return $this;
    }

    /**
     * Get banKey
     *
     * @return string
     */
    public function getBanKey()
    {
        return $this->banKey;
    }

    /**
     * Set banTipo
     *
     * @param string $banTipo
     *
     * @return Bannos
     */
    public function setBanTipo($banTipo)
    {
        $this->banTipo = $banTipo;

        return $this;
    }

    /**
     * Get banTipo
     *
     * @return string
     */
    public function getBanTipo()
    {
        return $this->banTipo;
    }

    /**
     * Set banMarca
     *
     * @param string $banMarca
     *
     * @return Bannos
     */
    public function setBanMarca($banMarca)
    {
        $this->banMarca = $banMarca;

        return $this;
    }

    /**
     * Get banMarca
     *
     * @return string
     */
    public function getBanMarca()
    {
        return $this->banMarca;
    }

    /**
     * Set banModelo
     *
     * @param string $banModelo
     *
     * @return Bannos
     */
    public function setBanModelo($banModelo)
    {
        $this->banModelo = $banModelo;

        return $this;
    }

    /**
     * Get banModelo
     *
     * @return string
     */
    public function getBanModelo()
    {
        return $this->banModelo;
    }

    /**
     * Set banAsignado
     *
     * @param integer $banAsignado
     *
     * @return Bannos
     */
    public function setBanAsignado($banAsignado)
    {
        $this->banAsignado = $banAsignado;

        return $this;
    }

    /**
     * Get banAsignado
     *
     * @return integer
     */
    public function getBanAsignado()
    {
        return $this->banAsignado;
    }

    /**
     * Set banComentario
     *
     * @param string $banComentario
     *
     * @return Bannos
     */
    public function setBanComentario($banComentario)
    {
        $this->banComentario = $banComentario;

        return $this;
    }

    /**
     * Get banComentario
     *
     * @return string
     */
    public function getBanComentario()
    {
        return $this->banComentario;
    }

    /**
     * Set banFecharegistro
     *
     * @param \DateTime $banFecharegistro
     *
     * @return Bannos
     */
    public function setBanFecharegistro($banFecharegistro)
    {
        $this->banFecharegistro = $banFecharegistro;

        return $this;
    }

    /**
     * Get banFecharegistro
     *
     * @return \DateTime
     */
    public function getBanFecharegistro()
    {
        return $this->banFecharegistro;
    }

    /**
     * Set banSucursalFk
     *
     * @param \BaseBundle\Entity\Sucursal $banSucursalFk
     *
     * @return Bannos
     */
    public function setBanSucursalFk(\BaseBundle\Entity\Sucursal $banSucursalFk = null)
    {
        $this->banSucursalFk = $banSucursalFk;

        return $this;
    }

    /**
     * Get banSucursalFk
     *
     * @return \BaseBundle\Entity\Sucursal
     */
    public function getBanSucursalFk()
    {
        return $this->banSucursalFk;
    }

    /**
     * Set banTipo2
     *
     * @param \BaseBundle\Entity\BannosTipo $banTipo2
     *
     * @return Bannos
     */
    public function setBanTipo2(\BaseBundle\Entity\BannosTipo $banTipo2 = null)
    {
        $this->banTipo2 = $banTipo2;

        return $this;
    }

    /**
     * Get banTipo2
     *
     * @return \BaseBundle\Entity\BannosTipo
     */
    public function getBanTipo2()
    {
        return $this->banTipo2;
    }

    /**
     * Set banClienteFk
     *
     * @param \BaseBundle\Entity\Cliente $banClienteFk
     *
     * @return Bannos
     */
    public function setBanClienteFk(\BaseBundle\Entity\Cliente $banClienteFk = null)
    {
        $this->banClienteFk = $banClienteFk;

        return $this;
    }

    /**
     * Get banClienteFk
     *
     * @return \BaseBundle\Entity\Cliente
     */
    public function getBanClienteFk()
    {
        return $this->banClienteFk;
    }
}
