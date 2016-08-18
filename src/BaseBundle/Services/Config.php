<?php
namespace BaseBundle\Services;

class Config
{
	private $privilegios = array();

	public function __construct()
	{
		// privilegios
		$this->privilegios = array(
			1 => 'Usuarios',
			2 => 'Clientes',
			3 => 'Sucursales',
			4 => 'Arriendos',
			5 => 'Bodega',
			6 => 'Mantenciones'
		);
	}

/**
GETTERS
**/
	public function getAllPrivilegios($json = false)
	{
		$listaPrivilegios = array();
		foreach($this->privilegios as $key => $value)
		{
			$listaPrivilegios[$value] = true;
		}
		return ($json == 'json')?json_encode(array('permisos' => $listaPrivilegios)): array('permisos' => $listaPrivilegios);
	}

	public function getAllPrivilegios2($json = false)
	{
		return ($json == 'json')?json_encode($this->privilegios): $this->privilegios;
	}

	public function getArrayPrivilegios($arr, $json = false)
	{
		if(is_array($arr))
		{
			$arrPrivilegios = array();
			foreach($arr as $key=>$value)
			{
				$arrPrivilegios[$this->privilegios[$value]] = true;
			}
			
			return ($json == 'json')?json_encode(array('permisos' => $arrPrivilegios)):array('permisos' => $arrPrivilegios);

		}else{
			return false;
		}

	}

}