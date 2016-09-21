<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\Bitacora;
use BaseBundle\Entity\UsuarioLog;
use \stdClass;

class AgregarRutaController extends Controller
{
    public function agregarRutaAction(Request $request, $id)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

    	if(is_numeric($id) && $id > 0)
    	{
    		// variables
            $em 		= $this->getDoctrine()->getManager();
            $qb 		= $em->createQueryBuilder();
            $listaDiasA = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
            $listaDiasB = array(1, 2, 3, 4, 5, 6, 7);

            // servicios
            $defaultData = $this->get('service.default.data');
            $defaultData->setHtmlHeader(array('title' => 'Inicio'));
            $userData = $this->get('service.user.data');

            $detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $id, 'dcoActivo' => 1));

            $ruta = array(
                'nombre_cliente' => '---',
                'detalle' => array()
                );
            $listaCamiones = array();
            if($detalleVenta)
            {
                // cargar datos de ruta
                foreach($detalleVenta as $value)
                {
                    $datos = new stdClass();

                    // informacion de detalle
                    $datos->id_detalle      = $value->getDcoIdPk();
                    $datos->direccion       = $value->getDcoDireccion();
                    $datos->servicio        = $value->getDcoServicioFk()->getSerNombre();
                    $datos->cbanos          = $value->getDcoCbano();
                    $datos->ccasetas        = $value->getDcoCcaseta();
                    $datos->cduchas         = $value->getDcoCducha();
                    $datos->cexternos       = $value->getDcoCexterno();
                    $datos->clavamanos      = $value->getDcoClavamano();
                    $datos->comuna          = $value->getDcoComunaFk()->getComNombre();
                    $datos->provincia       = $value->getDcoComunaFk()->getComProvinciaFk()->getProNombre();
                    $datos->region          = $value->getDcoComunaFk()->getComProvinciaFk()->getProRegionFk()->getRegNombre();

                    $ruta['nombre_cliente'] = $value->getDcoVentaFk()->getVenClienteFk()->getCliNombre();

                    // informacion de ruta
                    $rutaVenta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $datos->id_detalle, 'rutActivo' => 1));

                    if($rutaVenta)
                    {
                        foreach($rutaVenta as $value2)
                        {
                            $datos2 = new stdClass();

                            $datos2->id         = $value2->getRutIdPk();
                            $datos2->id_camion  = ($value2->getRutCamionFk())?$value2->getRutCamionFk()->getCamIdPk():null;
                            $datos2->dia        = $value2->getRutDia();
                            $datos2->nombre_dia = str_replace($listaDiasB, $listaDiasA, $datos2->dia);

                            $datos->ruta[] = $datos2;
                        }
                    }

                    $ruta['detalle'][] = $datos;
                }

                // cargar camiones
                $camiones = $em->getRepository('BaseBundle:Camion')->findBy(array('camSucursalFk' => $userData->getUserData()->sucursalActiva));

            	if($camiones)
            	{
            		foreach($camiones as $key => $value2)
            		{
            			$listaCamiones[$key]['id_camion'] 		= $value2->getCamIdPk();
            			$listaCamiones[$key]['patente_camion'] 	= $value2->getCamPatente();
            			$listaCamiones[$key]['nombre_chofer'] 	= $value2->getCamUsuarioFk()->getUsuNombre();
            		}
            	}


            }
            // echo '<pre>';print_r($ruta);exit;

        	return $this->render('MantencionBundle::agregarRuta.html.twig', array(
                'defaultData' => $defaultData->getAll(),
                'userData'      => $userData->getUserData(),
        		'datosRuta' => $ruta,
        		'camiones'	=> $listaCamiones,
                'id'        => $id
        		));
        }else{
            echo 'error';exit;
    	}
    }

    public function guardarRutaAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $select_camion = ($request->get('select_camion', false))? $request->get('select_camion'): null;

        // print_r($select_camion);exit;

        if( $select_camion )
        {
            $em = $this->getDoctrine()->getManager();
            $userData       = $this->get('service.user.data');

            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));
            $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $userData->getUserData()->id ));

            // registrar en bitacora de ventas y logs de usuario

            // recorrer el detalle
            foreach($select_camion as $key => $value)
            {
                // $detalleRuta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $key));

                // recorrer ruta de detalle
                foreach($value as $key2 => $value2)
                {
                    if($ruta = $em->getRepository('BaseBundle:Ruta')->findOneBy(array('rutIdPk' => $key2)))
                    {
                        if($value2 != 'default')
                        {
                            $dia = $ruta->getRutDia();
                            $camion = $em->getRepository('BaseBundle:Camion')->findOneBy(array('camIdPk' => $value2));

                            $ruta->setRutCamionFk($camion);
                            $em->persist($ruta);
                            $em->flush();

                            // foraneas
                            $fkVenta = $em->getRepository('BaseBundle:Venta')->findOneBy(array('venIdPk' => $ruta->getRutDetallecontratoFk()->getDcoVentaFk()->getVenIdPk() ));
                            
                            $listaDiasA = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
                            $listaDiasB = array(1, 2, 3, 4, 5, 6, 7);

                            $defaultText = $this->get('service.default.text');

                            // bitacora venta
                            $defaultText->setBitacoraVenta('agregar_ruta', array('dia' => str_replace($listaDiasB, $listaDiasA, $dia), 'camion' => $camion->getCamPatente(), 'usuario' => $userData->getUserData()->nombre.' '.$userData->getUserData()->apellido ));

                            $bitacora = new Bitacora();
                            $bitacora->setBitFecha(new \DateTime(date("Y-m-d H:i:s")));
                            $bitacora->setBitDescripcion($defaultText->getBitacoraVenta('agregar_ruta'));
                            $bitacora->setBitSucursalFk($fkSucursal);
                            $bitacora->setBitVentaFk($fkVenta);
                            $em->persist($bitacora);
                            $em->flush();

                            // log usuario
                            $defaultText->setLogUsuario('agregar_ruta', array('dia' => str_replace($listaDiasB, $listaDiasA, $dia), 'camion' => $camion->getCamPatente(), 'cliente' => $fkVenta->getVenClienteFk()->getCliNombre(), 'id' => $fkVenta->getVenIdPk() ));

                            $logUsu = new UsuarioLog();
                            $logUsu->setUloFecha(new \DateTime(date("Y-m-d H:i:s")));
                            $logUsu->setUloDescripcion($defaultText->getLogUsuario('agregar_ruta'));
                            $logUsu->setUloUsuarioFk($fkUsuario);
                            $logUsu->setUloSucursalFk($fkSucursal);
                            $em->persist($logUsu);
                            $em->flush();

                            $defaultText->setResset();

                            // echo $dia.'-';
                        }
                    }
                }
            }

            $result = true;
        }else{
            $result = false;
        }

        echo json_encode(array('result' => $result));
        exit;
    }
}
