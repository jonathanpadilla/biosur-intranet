<?php
namespace BaseBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use \stdClass;

class UserData
{
	private $session;
	// todos los datos
	private $userData 		= array();
	private $sucursal 		= array();
	private $configuracion 	= array();
	private $contacto 		= array();

	// validar session
	private $validar 		= null;

	public function __construct(Session $session)
	{
		$this->session = $session;

		// cargar userdata si existe session
		if($userData = $this->session->get('userData'))
		{
			$userData = json_decode($userData);

			$this->userData 		= $userData;
			$this->sucursal 		= $userData->sucursales;
			$this->configuracion 	= $userData->configuracion;
			$this->contacto 		= $userData->contactos;
		}
	}

/**
SETTERS
**/
	public function setUserData($userData)
	{
		if(is_array($userData))
		{
			// crear session
			$this->session->set('userData', json_encode($userData));

			if($this->session->get('userData'))
			{
				$this->userData 		= $userData;
				$this->sucursal 		= $userData['sucursales'];
				$this->configuracion 	= $userData['configuracion'];
				$this->contacto 		= $userData['contactos'];

				$result = true;
			}else{
				$result = false;
			}
		}else{
			$result = false;
		}

		return $result;
	}

	public function setSucursalActiva($id)
	{
		if(is_numeric($id))
		{

			$this->userData->sucursalActiva = $id;

			$this->session->set('userData', json_encode($this->userData));

			$result = true;
		}else{
			$result = false;
		}

		return $result;
	}

	public function setSucursal($arr)
	{
		if(is_array($arr))
		{

			$datos = new stdClass();

			$datos->id 				= $arr['id'];
			$datos->sucursalId 		= $arr['sucursalId'];
			$datos->sucursalNombre 	= $arr['sucursalNombre'];
			$datos->activa 			= $arr['activa'];

			array_push($this->userData->sucursales, $datos);

			$this->session->set('userData', json_encode($this->userData));

			$result = true;
		}else{
			$result = false;
		}

		return $result;

	}

/**
GETTERS
**/
	public function getUserData()
	{
		return $this->userData;
	}

	public function getSucursales()
	{
		return $this->sucursal;
	}

	public function getConfiguracion()
	{
		return $this->configuracion;
	}

	public function getContacto()
	{
		return $this->contacto;
	}

	// validar session
	public function ValidarSession($permiso = false)
	{
		$this->validar = $this->session->get('userData');
		$result = false;

		if($permiso)
		{
			if($this->validar)
			{

				if(array_key_exists($permiso, $this->configuracion->permisos) && $this->configuracion->permisos->$permiso )
				{
					$result = true;
				}

			}
		}else{

			if($this->validar)
			{
				$result = true;
			}
		}

		return $result;
	}

	public function cerrarSession()
	{
		$this->validar = $this->session->get('userData');
		$result = false;

		if($this->validar)
		{
			$this->session->remove('userData');
			$result = true;
		}

		return $result;
	}

}