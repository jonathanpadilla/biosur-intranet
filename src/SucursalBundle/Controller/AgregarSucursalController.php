<?php

namespace SucursalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\UsuarioNnSucursal;
use BaseBundle\Entity\Sucursal;
use \stdClass;

class AgregarSucursalController extends Controller
{
    public function agregarSucursalAction()
    {

    	// validar session
        if(!$this->get('service.user.data')->ValidarSession('Sucursales')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Agregar sucursal'));
        $userData = $this->get('service.user.data');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

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

                $datos->id      = $value->getComIdPk();
                $datos->nombre  = $value->getComNombre();

                $listaComunas[] = $datos;
            }

        }else{
            $listaComunas = null;
        } 

        return $this->render('SucursalBundle::agregar_sucursal.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
            'listaComunas'  => $listaComunas,
            'menuActivo'    => 'agregarsucursal'
            ));
    }

    public function guardarSucursalAction(Request $request)
    {
        $result = false;

        if( $request->getMethod() == 'POST' )
        {
            $text_nombre            = ($request->get('text_nombre', false))         ?$request->get('text_nombre')           :null;
            $text_giro              = ($request->get('text_giro', false))           ?$request->get('text_giro')             :null;
            $select_comuna          = ($request->get('select_comuna', false))       ?$request->get('select_comuna')         :null;
            $text_direccion         = ($request->get('text_direccion', false))      ?$request->get('text_direccion')        :null;
            $textarea_comentario    = ($request->get('textarea_comentario', false)) ?$request->get('textarea_comentario')   :null;

            $em = $this->getDoctrine()->getManager();

            // servicios
            $userData = $this->get('service.user.data');


            // claves foraneas
            $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $userData->getUserData()->id ));
            $fkComuna   = $em->getRepository('BaseBundle:Comuna')->findOneBy(array('comIdPk' => $select_comuna ));

            $sucursal = new Sucursal();
            $sucursal->setSucUsuarioFk($fkUsuario);
            $sucursal->setSucComunaFk($fkComuna);
            $sucursal->setSucNombre($text_nombre);
            $sucursal->setSucGiro($text_giro);
            $sucursal->setSucDireccion($text_direccion);
            $sucursal->setSucFecharegistro( new \DateTime(date("Y-m-d H:i:s")) );
            $sucursal->setSucComentario($textarea_comentario);
            $em->persist($sucursal);
            $em->flush();

            if($sucursal->getSucIdPk())
            {
                $unns = new UsuarioNnSucursal();
                $unns->setUnnsSucursalFk($sucursal);
                $unns->setUnnsUsuarioFk($fkUsuario);
                $unns->setUnnsHabilitado(1);
                $unns->setUnnsFecharegistro( new \DateTime(date("Y-m-d H:i:s")) );

                $em->persist($unns);
                $em->flush();

                $arr = array(
                    'id' => $unns->getUnnsIdPk(),
                    'sucursalId' => $sucursal->getSucIdPk(),
                    'sucursalNombre' => $text_nombre,
                    'activa' => 0
                    );
                
                $userData->setSucursal($arr);
            }

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }
}
