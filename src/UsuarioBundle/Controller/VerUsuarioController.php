<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\Usuario;
use BaseBundle\Entity\UsuarioNnSucursal;
use BaseBundle\Entity\Contacto;
use \stdClass;

class VerUsuarioController extends Controller
{

/**
VISTAS
*/
    public function verAction($id)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Usuarios')){return $this->redirectToRoute('base_vista_ingreso');}

        if(is_numeric($id) && $id > 0)
        {
            // variables
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();

            // servicios
            $defaultData = $this->get('service.default.data');
            $defaultData->setHtmlHeader(array('title' => 'Inicio'));
            $userData = $this->get('service.user.data');

            $usuario = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $id));

            if($usuario && $usuario->getUsuVinculado() != 0)
            {
                // datos usuario
                $infoUsuario = array(
                    'id'                => $usuario->getUsuIdPk(),
                    'nombre'            => $usuario->getUsuNombre(),
                    'apellido'          => $usuario->getUsuApellido(),
                    'rut'               => $usuario->getUsuRut(),
                    'direccion'         => $usuario->getUsuDireccion(),
                    'tipo'              => (($usuario->getUsuTipo() == 1)?'Adminitrador':'Ejecutivo'),
                    'fecha_registro'    => $usuario->getUsuFecharegistro()->format('d/m/Y'),
                    'comentario'        => $usuario->getUsuComentario()
                    );
                $configUsuario = json_decode($usuario->getUsuConfiguracion());

                // datos de contacto
                
                $q  = $qb->select(array('c'))
                    ->from('BaseBundle:Contacto', 'c')
                    ->where(
                        $qb->expr()->eq('c.conUsuarioFk', $usuario->getUsuIdPk())
                        )
                    ->getQuery();

                $resultQuery = $q->getResult();

                if($resultQuery)
                {
                    $contactoUsuario = array();
                    foreach($resultQuery as $value)
                    {
                        $datos = new stdClass();

                        $datos->id          = $value->getConTipoFk()->getCtiIdPk();
                        $datos->persona     = $value->getConNombrepersona();
                        $datos->contacto    = $value->getConDetalle();
                        $datos->tipo        = $value->getConTipoFk()->getCtiNombre();

                        $contactoUsuario[] = $datos;
                    }

                }else{
                    $contactoUsuario = array();
                }
                // echo '<pre>';
                // print_r($contactoUsuario);exit;

                return $this->render('UsuarioBundle::verUsuario.html.twig', array(
                    'defaultData'   => $defaultData->getAll(),
                    'userData'      => $userData->getUserData(),
                    'infoUsuario'   => $infoUsuario,
                    'configUsuario' => $configUsuario,
                    'contactos'     => $contactoUsuario));

            }else{
                return $this->redirectToRoute('usuario_vista_listar');
            }
            
        }else{
            return $this->redirectToRoute('usuario_vista_listar');
        }
        
    }

/**
FUNCIONES SIN VISTA
*/

/**
FUNCIONES AJAX
*/
}
