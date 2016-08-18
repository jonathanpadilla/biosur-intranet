<?php
namespace BaseBundle\Services;

class DefaultText
{
	private $bitacoraVenta  	= array();
	private $logUsuario  		= array();

	private $defBitacoraVenta 	= array();
	private $defLogUsuario 		= array();

	private $search 			= array();
	private $replace 			= array();

	private $result				= false;

	public function __construct()
	{
		// textos para bitacora de venta
		$this->defBitacoraVenta = array(
			'guardar_venta' 	=> '<strong>Arriendo registrado</strong> al sistema por <b class="text-primary">[usuario]</b>',
			'asignar_bannos' 	=> '<strong>Modificación de [cantidad] productos</strong> ([detalle]) al arriendo, por <b class="text-primary">[usuario]</b>',
			'agregar_ruta'		=> '<strong>Modificación de ruta</strong> del día [dia] al camión [camion], por <b class="text-primary">[usuario]</b>',
			'asignar_lavamanos'	=> '<strong>Asignación de lavamanos</strong> (L[lavamanos]) al baño B[banno], por <b class="text-primary">[usuario]</b>',
			);

		$this->bitacoraVenta = $this->defBitacoraVenta;
		// textos para logs de usuario
		$this->defLogUsuario = array(
			'guardar_venta' 	=> '<strong>Registro de nuevo arriendo</strong> al sistema para <b class="text-system">[cliente]</b> (Arriendo N° [id])',
			'asignar_bannos' 	=> '<strong>Modificación de [cantidad] productos</strong> ([detalle]) al arriendo de <b class="text-system">[cliente]</b> (Arriendo N° [id])',
			'agregar_ruta'		=> '<strong>Modificación de ruta</strong> del día [dia] al camión [camion] al arriendo de <b class="text-system">[cliente]</b> (Arriendo N° [id])',
			'asignar_lavamanos' => '<strong>Asignación de lavamanos</strong> (L[lavamanos]) al baño B[banno] al arriendo de <b class="text-system">[cliente]</b> (Arriendo N° [id])'
			);

		$this->logUsuario = $this->defLogUsuario;

		// reemplazar (agregar registros en orden, ambos arrays)
		$this->search = array(
			'usuario' 	=> '[usuario]',
			'cliente'	=> '[cliente]',
			'id'		=> '[id]',
			'cantidad'	=> '[cantidad]',
			'detalle' 	=> '[detalle]',
			'dia'		=> '[dia]',
			'camion'	=> '[camion]',
			'lavamanos'	=> '[lavamanos]',
			'banno'		=> '[banno]',
			);

		$this->replace = array(
			'usuario'	=> 'usuario',
			'cliente'	=> 'cliente',
			'id'		=> 'id-0',
			'cantidad'	=> 'c-0',
			'detalle'	=> 'd-0',
			'dia'		=> 'día',
			'camion'	=> 'p-0',
			'lavamanos'	=> 'l-0',
			'banno'		=> 'b-0',
			);
	}

/**
GETTERS
**/
	public function getBitacoraVenta($item = false)
	{
		if($item)
		{
			$result = $this->bitacoraVenta[$item];
		}else{
			$result = $this->bitacoraVenta;
		}

		return $result;
	}

	public function getLogUsuario($item = false)
	{
		if($item)
		{
			$result = $this->logUsuario[$item];
		}else{
			$result = $this->logUsuario;
		}

		return $result;
	}

/**
SETTERS
**/
	public function setBitacoraVenta($name = false, $arr = false)
	{
		if($name && $arr)
		{
			foreach($arr as $key => $value)
			{
				$this->replace[$key] = $value;
			}

			$this->bitacoraVenta[$name] = str_replace($this->search, $this->replace, $this->bitacoraVenta[$name]);

			$this->result = true;
		}

		return $this->result;
	}

	public function setLogUsuario($name = false, $arr = false)
	{
		if($name && $arr)
		{
			foreach($arr as $key => $value)
			{
				$this->replace[$key] = $value;
			}

			$this->logUsuario[$name] = str_replace($this->search, $this->replace, $this->logUsuario[$name]);

			$this->result = true;
		}

		return $this->result;
	}

	public function setResset()
	{
		$this->bitacoraVenta  	= $this->defBitacoraVenta;
		$this->logUsuario  		= $this->defLogUsuario;
		

		return true;
	}

}