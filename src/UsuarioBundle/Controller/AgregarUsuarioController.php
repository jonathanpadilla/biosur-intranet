<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\Usuario;
use BaseBundle\Entity\UsuarioNnSucursal;
use BaseBundle\Entity\Contacto;
use \stdClass;

class AgregarUsuarioController extends Controller
{

/**
VISTAS
*/
    public function agregarUsuarioAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
    	$em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Inicio'));
        $userData = $this->get('service.user.data');

        // cargar comunas
        $q  = $qb->select(array('c'))
            ->from('BaseBundle:Comuna', 'c')
            ->getQuery();
        $resultQuery = $q->getResult();

        $listaComunas = array();
        if($resultQuery)
        {
        	foreach($resultQuery as $value)
        	{
        		$datos = new stdClass();

        		$datos->id 		= $value->getComIdPk();
                $datos->nombre  = $value->getComNombre();

        		$listaComunas[] = $datos;
        	}

        }else{
            $listaComunas = null;
        }     

        // cargar privilegios
        $userConfig = $this->get('service.config');
        $privilegios = $userConfig->getAllPrivilegios2();

        return $this->render('UsuarioBundle::agregarUsuario.html.twig', array(
            'defaultData' => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
        	'comunas'      => $listaComunas,
            'privilegios'  => $privilegios
        	));
    }

/**
FUNCIONES SIN VISTA
*/
    public function guardarUsuarioAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}
        
        if( $request->getMethod() == 'POST' )
        {
        // exit;
            // datos post
            $input_nombre           = ($request->get('input_nombre', false))?       ucfirst($request->get('input_nombre')):     null;
            $input_apellido         = ($request->get('input_apellido', false))?     ucfirst($request->get('input_apellido')):   null;
            $input_rut              = ($request->get('input_rut', false))?          $request->get('input_rut'):                 null;
            $input_direccion        = ($request->get('input_direccion', false))?    ucfirst($request->get('input_direccion')):  null;
            $select_tipousuario     = ($request->get('select_tipousuario', false))? (int)$request->get('select_tipousuario'):   null;
            $input_clave            = ($request->get('input_clave', false))?        $request->get('input_clave'):               null;
            $select_privilegios     = ($request->get('select_privilegios', false))? $request->get('select_privilegios'):        null;
            $textarea_comentario    = ($request->get('textarea_comentario', false))?$request->get('textarea_comentario'):       null;
            $comIdPk                = ($request->get('select_comuna', false))?      (int)$request->get('select_comuna'):        null;
            $contactos              = ($request->get('datocontacto', false))?       $request->get('datocontacto'):              null;

            // cargar privilegios
            $userConfig = $this->get('service.config');

            if($select_tipousuario == 1)
            {
                $privilegios = $userConfig->getAllPrivilegios('json');
            }else{
                $privilegios = $userConfig->getArrayPrivilegios($select_privilegios, 'json');
            }

            $em = $this->getDoctrine()->getManager();

            // guardar usuario
            // claves foraneas
            $fkComuna = $em->getRepository('BaseBundle:Comuna')
                            ->find($comIdPk);

            $usuario = new Usuario();
            $usuario->setUsuComunaFk($fkComuna);
            $usuario->setUsuNombre($input_nombre);
            $usuario->setUsuApellido($input_apellido);
            $usuario->setUsuRut($input_rut);
            $usuario->setUsuDireccion($input_direccion);
            $usuario->setUsuClave(crypt($input_clave, '$6$rounds=5000$usesomesillystringforsalt$'));
            $usuario->setUsuConfiguracion($privilegios);
            $usuario->setUsuVinculado(1);
            $usuario->setUsuTipo($select_tipousuario);
            $usuario->setUsuFecharegistro(new \DateTime(date("Y-m-d H:i:s")));
            $usuario->setUsuComentario($textarea_comentario);

            $em->persist($usuario);
            $em->flush();

            // guardar sucursal de usuario y contactos
            if($usuario->getUsuIdPk())
            {
                // sucursal
                $sucIdPk = 2;
                $fkSucursal = $em->getRepository('BaseBundle:Sucursal')
                                ->find($sucIdPk);

                $usuarioSucursal = new UsuarioNnSucursal();
                $usuarioSucursal->setUnnsUsuarioFk($usuario);
                $usuarioSucursal->setUnnsSucursalFk($fkSucursal);
                $usuarioSucursal->setUnnsHabilitado(1);
                $usuarioSucursal->setUnnsFecharegistro(new \DateTime(date("Y-m-d H:i:s")));

                $em->persist($usuarioSucursal);
                $em->flush();

                // contactos
                if(is_array($contactos))
                {
                    foreach($contactos as $value)
                    {
                        // claves foraneas
                        $fkTipo = $em->getRepository('BaseBundle:ContactoTipo')
                                    ->findOneByCtiIdPk($value[1]);

                        $contactoUsuario = new Contacto();
                        $contactoUsuario->setConTipoFk($fkTipo);
                        $contactoUsuario->setConUsuarioFk($usuario);
                        $contactoUsuario->setConDetalle($value[2]);
                        $contactoUsuario->setConNombrepersona(ucfirst($value[3]));

                        $em->persist($contactoUsuario);
                        $em->flush();

                    }
                }

                return $this->redirectToRoute('usuario_vista_listar');

            }else{
                return $this->redirectToRoute('usuario_vista_agregar');
            }            

            exit;
            
        }else{
            return $this->redirectToRoute('usuario_vista_agregar');
        }
        exit;
    }

/**
FUNCIONES AJAX
*/
}
