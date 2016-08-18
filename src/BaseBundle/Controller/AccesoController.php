<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use \stdClass;

class AccesoController extends Controller
{
    public function ingresoAction()
    {
        return $this->render('BaseBundle::ingreso.html.twig');
    }

    public function validarIngresoAction(Request $request)
    {

    	if( $request->getMethod() == 'POST' )
        {
        	// variables
    		$em 			= $this->getDoctrine()->getManager();
    		$datosUsuario 	= array();

        	// datos post
    		$rut 	= $request->get('username', false);
    		$clave 	= $request->get('password', false);

    		// validar que usuario existe
    		if($usuario = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuRut' => $rut, 'usuVinculado' => 1)))
    		{
    			// validar clave
    			if(crypt($clave, '$6$rounds=5000$usesomesillystringforsalt$') === $usuario->getUsuClave())
    			{
    				// datos usuario
    				$datosUsuario['id'] 			= $usuario->getUsuIdPk();
    				$datosUsuario['nombre'] 		= $usuario->getUsuNombre();
    				$datosUsuario['apellido'] 		= $usuario->getUsuApellido();
    				$datosUsuario['rut'] 			= $usuario->getUsuRut();
    				$datosUsuario['direccion'] 		= $usuario->getUsuDireccion();
    				$datosUsuario['comuna'] 		= $usuario->getUsuComunaFk()->getComNombre();
    				$datosUsuario['provincia'] 		= $usuario->getUsuComunaFk()->getComProvinciaFk()->getProNombre();
    				$datosUsuario['region'] 		= $usuario->getUsuComunaFk()->getComProvinciaFk()->getProRegionFk()->getRegNombre();
    				$datosUsuario['vinculado'] 		= $usuario->getUsuVinculado();
    				$datosUsuario['cookie'] 		= $usuario->getUsuCookie();
    				$datosUsuario['tipo'] 			= $usuario->getUsuTipo();
    				$datosUsuario['fechaRegistro'] 	= $usuario->getUsuFecharegistro()->format('d/m/Y');
    				$datosUsuario['comentario'] 	= $usuario->getUsuComentario();
    				$datosUsuario['configuracion'] 	= json_decode($usuario->getUsuConfiguracion());

    				// contacto del usuario
    				if($contacto = $em->getRepository('BaseBundle:Contacto')->findBy(array('conUsuarioFk' => $usuario->getUsuIdPk())))
    				{
    					foreach ($contacto as $value)
    					{
    						$datos = new stdClass();

    						$datos->id 			= $value->getConIdPk();
    						$datos->tipoId 		= $value->getConTipoFk()->getCtiIdPk();
    						$datos->tipoNombre	= $value->getConTipoFk()->getCtiNombre();
    						$datos->detalle		= $value->getConDetalle();
    						$datos->nombre		= $value->getConNombrepersona();

    						$datosUsuario['contactos'][] = $datos;
    					}
    				}

    				// sucursales usuario
    				if($sucursal = $em->getRepository('BaseBundle:UsuarioNnSucursal')->findBy(array('unnsUsuarioFk' => $usuario->getUsuIdPk(), 'unnsHabilitado' => 1 )))
    				{
                        $activa = 1;
    					foreach ($sucursal as $value)
    					{
    						$datos = new stdClass();

    						$datos->id 				= $value->getUnnsIdPk();
    						$datos->sucursalId 		= $value->getUnnsSucursalFk()->getSucIdPk();
    						$datos->sucursalNombre 	= $value->getUnnsSucursalFk()->getSucNombre();
                            $datos->activa          = $activa;

                            if($datos->activa == 1)
                            {
                                $datosUsuario['sucursalActiva'] = $datos->sucursalId;
                            }

                            $activa = 0;
    						$datosUsuario['sucursales'][] = $datos;
    					}
    				}

    				$userData = $this->get('service.user.data');
    				if($userData->setUserData($datosUsuario))
                    {
                        $result = true;
                    }else{
    				    $result = false;
                    }

    			}else{
    				$result = false;
    			}
    		}else{
                $result = false;
            }

        }else{
        	$result = false;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function salirAction()
    {
        if($this->get('service.user.data')->cerrarSession())
        {
            return $this->redirectToRoute('base_vista_ingreso');
        }else{
            return $this->redirectToRoute('base_vista_homepage');
        }
    }

    public function ingresoAppAction(Request $request)
    {
        // variables
        $result = false;
        $datos  = array();

        if( $request->getMethod() == 'POST' )
        {
            
            if($postdata = file_get_contents("php://input"))
            {
                // variables
                $post   = json_decode($postdata);
                $rut    = $post->rut;
                $clave  = $post->clave;
                $em     = $this->getDoctrine()->getManager();

                if($usuario = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuRut' => $rut, 'usuVinculado' => 1)))
                {
                    if(crypt($clave, '$6$rounds=5000$usesomesillystringforsalt$') === $usuario->getUsuClave())
                    {
                        $datos['id']         = $usuario->getUsuIdPk();
                        $datos['nombre']     = $usuario->getUsuNombre();
                        $datos['apellido']   = $usuario->getUsuApellido();
                        $datos['key']        = '$2a$07$jpdeveloperstringforsOk0vkvMOVVB8r722xvEQLXl//e23GFq.';

                        $result = true;
                    }
                }

            }

        }

        echo json_encode(array('result' => $result, 'datos' => $datos));
        exit;
    }
}