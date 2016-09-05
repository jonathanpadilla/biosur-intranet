<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class VerRutasController extends Controller
{
    public function verRutasAction(Request $request, $dia, $camion)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Inicio'));
        $userData = $this->get('service.user.data');
        $order = $this->get('service.global.function');

        // variables
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();
        $qb2            = $em->createQueryBuilder();
        $listaDestinos  = array();
        $listaDiasA     = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
        $listaDiasB     = array(1, 2, 3, 4, 5, 6, 7);
        $nombreChofer   = '';
        $listaChofer    = array();

        $q  = $qb->select(array('v'))
                ->from('BaseBundle:Venta', 'v')
                ->where('v.venFinalizado = 1 AND v.venSucursalFk = '.$userData->getUserData()->sucursalActiva)
                ->getQuery();

        if($resultQuery = $q->getResult())
        {
            foreach($resultQuery as $value)
            {
                if($detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $value->getVenIdPk() )) )
                {
                    foreach($detalleVenta as $value2)
                    {

                        if($ruta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $value2->getDcoIdPk() ), array('rutIdPk' => 'ASC')) )
                        {
                            foreach ($ruta as $value3){

                                if($value3->getRutDia() == $dia && $value3->getRutCamionFk()->getCamIdPk() == $camion)
                                {
                                    $datos = new stdClass();

                                    $datos->ruta_id                 = $value3->getRutIdPk();
                                    $datos->ruta_dia                = $value3->getRutDia();
                                    $datos->ruta_orden              = $value3->getRutOrden();
                                    $datos->ruta_camion             = $value3->getRutCamionFk()->getCamIdPk();
                                    $datos->ruta_patente            = $value3->getRutCamionFk()->getCamPatente();
                                    $datos->ruta_chofer_nombre      = $value3->getRutCamionFk()->getCamUsuarioFk()->getUsuNombre();
                                    $datos->ruta_chofer_apellido    = $value3->getRutCamionFk()->getCamUsuarioFk()->getUsuApellido();

                                    $datos->detalle_id          = $value2->getDcoIdPk();
                                    $datos->detalle_direccion   = $value2->getDcoDireccion();
                                    $datos->detalle_ciudad      = $value2->getDcoComunaFk()->getComNombre();
                                    $datos->detalle_cbano       = $value2->getDcoCbano();
                                    $datos->detalle_ccaseta     = $value2->getDcoCcaseta();
                                    $datos->detalle_cducha      = $value2->getDcoCducha();
                                    $datos->detalle_cexterno    = $value2->getDcoCexterno();
                                    $datos->detalle_clavamano   = $value2->getDcoClavamano();

                                    $datos->venta_id            = $value->getVenIdPk();
                                    $datos->venta_cliente       = $value->getVenClienteFk()->getCliNombre();

                                    $listaDestinos[] = $datos;

                                    $nombreChofer = $datos->ruta_chofer_nombre.' '.$datos->ruta_chofer_apellido;
                                }
                                
                            }
                        }
                    }
                }
            }
        }

        // camiones
        if($chofer = $em->getRepository('BaseBundle:Camion')->findBy(array('camActivo' => 1, 'camSucursalFk' => $userData->getUserData()->sucursalActiva )) )
        {
            foreach($chofer as $value)
            {
                $datos = new stdClass();

                $datos->id_camion       = $value->getCamIdPk();
                $datos->nombre_chofer   = $value->getCamUsuarioFk()->getUsuNombre();
                $datos->apellido_chofer = $value->getCamUsuarioFk()->getUsuApellido();

                $listaChofer[]  = $datos;
            }
        }

        return $this->render('MantencionBundle::verRutas.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
            'destinos'      => $order->orderArr($listaDestinos, 'ruta_orden'),
            'dia'           => $dia,
            'dia_nombre'    => str_replace($listaDiasB, $listaDiasA, $dia),
            'camion'        => $camion,
            'chofer'        => $nombreChofer,
            'listaChofer'   => $listaChofer
            ));
    }

    public function cargarRutaAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        $order = $this->get('service.global.function');

        $userData = $this->get('service.user.data');

        $result         = false;
        $dia            = ($request->get('dia', false))? $request->get('dia'): null;
        $camion         = ($request->get('camion', false))? $request->get('camion'): null;
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();
        $listaDestinos  = array();

        if($dia && $camion)
        {
            $q  = $qb->select(array('v'))
                ->from('BaseBundle:Venta', 'v')
                ->where('v.venFinalizado = 1 AND v.venSucursalFk = '.$userData->getUserData()->sucursalActiva)
                ->getQuery();

            if($resultQuery = $q->getResult())
            {
                foreach($resultQuery as $value)
                {
                    if($detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $value->getVenIdPk() )) )
                    {
                        foreach($detalleVenta as $value2)
                        {

                            if($ruta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $value2->getDcoIdPk() ), array('rutIdPk' => 'ASC')) )
                            {
                                foreach ($ruta as $value3){

                                    if($value3->getRutDia() == $dia && $value3->getRutCamionFk()->getCamIdPk() == $camion)
                                    {
                                        $datos = new stdClass();

                                        $datos->ruta_id                 = $value3->getRutIdPk();
                                        $datos->ruta_dia                = $value3->getRutDia();
                                        $datos->ruta_orden              = $value3->getRutOrden();
                                        // $datos->ruta_camion             = $value3->getRutCamionFk()->getCamIdPk();
                                        // $datos->ruta_patente            = $value3->getRutCamionFk()->getCamPatente();
                                        // $datos->ruta_chofer_nombre      = $value3->getRutCamionFk()->getCamUsuarioFk()->getUsuNombre();
                                        // $datos->ruta_chofer_apellido    = $value3->getRutCamionFk()->getCamUsuarioFk()->getUsuApellido();

                                        $datos->detalle_id          = $value2->getDcoIdPk();
                                        $datos->detalle_direccion   = $value2->getDcoDireccion();
                                        $datos->detalle_ciudad      = $value2->getDcoComunaFk()->getComNombre();
                                        $datos->detalle_cbano       = $value2->getDcoCbano();
                                        $datos->detalle_ccaseta     = $value2->getDcoCcaseta();
                                        $datos->detalle_cducha      = $value2->getDcoCducha();
                                        $datos->detalle_cexterno    = $value2->getDcoCexterno();
                                        $datos->detalle_clavamano   = $value2->getDcoClavamano();
                                        $datos->lat                 = $value2->getDcoLat();
                                        $datos->lon                 = $value2->getDcoLon();

                                        $datos->venta_id            = $value->getVenIdPk();
                                        $datos->venta_cliente       = $value->getVenClienteFk()->getCliNombre();

                                        $listaDestinos[] = $datos;

                                    }
                                    
                                }
                            }
                        }
                    }
                }

                $result = true;
            }


        }

        echo json_encode(array('result' => $result, 'ruta' => $order->orderArr($listaDestinos, 'ruta_orden')));
        exit;
    }

    public function guardarOrdenRutaAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        $result = false;

        $orden = ($request->get('orden', false))? $request->get('orden'): null;

        if($orden)
        {
            $em = $this->getDoctrine()->getManager();

            foreach($orden as $key => $value)
            {
                if($ruta = $em->getRepository('BaseBundle:Ruta')->findOneBy(array('rutIdPk' => $value )) )
                {
                    $ruta->setRutOrden($key);
                    $em->persist($ruta);
                    $em->flush();

                    $result = true;
                }
                
            }


        }

        echo json_encode(array('result' => $result));
        exit;
    }
}
