<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\DetcontratoNnBanno;
use BaseBundle\Entity\Bitacora;
use BaseBundle\Entity\UsuarioLog;
use \stdClass;

class AsignarBannosController extends Controller
{
    public function asignarBannosAction(Request $request, $id)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

    	// variables
    	$em 			= $this->getDoctrine()->getManager();
    	$listaBannos 	= array();
    	$detalles		= array();
        $nombreCliente  = '';

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Inicio'));
        $userData = $this->get('service.user.data');

        // cargar venta y cliente
        if($venta = $em->getRepository('BaseBundle:Venta')->findOneBy(array('venIdPk' => $id)))
        {
            $nombreCliente = $venta->getVenClienteFk()->getCliNombre();
        }

        // cargar select
    	if($bannos = $em->getRepository('BaseBundle:Bannos')->findBy(array('banSucursalFk' => 2)))
    	{
    		foreach($bannos as $value)
    		{
    			if(!$em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbBannoFk' => $value->getBanIdPk(), 'dnnbActivo' => 1 )))
    			{
    				$datos = new stdClass();

                    $letra  = '';
                    $tipo   = '';
                    switch($value->getBanTipo())
                    {
                        case 1:
                            $letra  = 'B';
                            $tipo   = 'Baño';
                            break;
                        case 2:
                            $letra  = 'C';
                            $tipo   = 'Caseta';
                            break;
                        case 3:
                            $letra  = 'D';
                            $tipo   = 'Ducha';
                            break;
                        case 4:
                            $letra  = 'E';
                            $tipo   = 'Baño externo';
                            break;
                        case 5:
                            $letra  = 'L';
                            $tipo   = 'Lavamanos';
                            break;
                    }

	    			$datos->id 		= str_pad($value->getBanIdPk(), 7, '0', STR_PAD_LEFT);
                    $datos->id      = $letra.$datos->id;
	    			$datos->tipo 	= $tipo;
                    $datos->tipo2   = ($value->getBanTipo2())?$value->getBanTipo2()->getBtiNombre():false;

	    			$listaBannos[]  = $datos;
    			}

    		}

    		// cargar detalles de arriendo
    		if($detalleContrato = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $id)))
    		{
    			foreach ($detalleContrato as $value)
    			{
                    $datos = new stdClass();

                    // cargar baños asignados
                    if($bannosAsignados = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value->getDcoIdPk(), 'dnnbActivo' => 1 )))
                    {
                        foreach($bannosAsignados as $value2)
                        {
                            $datos2 = new stdClass();

                            $letra  = '';
                            $tipo   = '';
                            switch($value2->getDnnbBannoFk()->getBanTipo())
                            {
                                case 1:
                                    $letra  = 'B';
                                    $tipo   = 'Baño';
                                    break;
                                case 2:
                                    $letra  = 'C';
                                    $tipo   = 'Caseta';
                                    break;
                                case 3:
                                    $letra  = 'D';
                                    $tipo   = 'Ducha';
                                    break;
                                case 4:
                                    $letra  = 'E';
                                    $tipo   = 'Baño externo';
                                    break;
                                case 5:
                                    $letra  = 'L';
                                    $tipo   = 'Lavamanos';
                                    break;
                            }
                            $datos2->tipo2  = ($value2->getDnnbBannoFk()->getBanTipo2())?$value2->getDnnbBannoFk()->getBanTipo2()->getBtiNombre() : '';
                            $datos2->id     = str_pad($value2->getDnnbBannoFk()->getBanIdPk(), 7, '0', STR_PAD_LEFT);
                            $datos2->id     = $letra.$datos2->id;
                            $datos2->tipo   = $tipo;

                            $datos->bannos[] = $datos2;
                        }
                    }else{
                        $datos->bannos[] = null;
                    }
                    // datos
    				$datos->id 			= $value->getDcoIdPk();
    				$datos->direccion 	= $value->getDcoDireccion();
    				$datos->comuna 		= $value->getDcoComunaFk()->getComNombre();
    				$datos->provincia 	= $value->getDcoComunaFk()->getComProvinciaFk()->getProNombre();
    				$datos->region 		= $value->getDcoComunaFk()->getComProvinciaFk()->getProRegionFk()->getRegNombre();
                    $datos->cbano       = $value->getDcoCbano();
                    $datos->ccaseta     = $value->getDcoCcaseta();
                    $datos->cducha      = $value->getDcoCducha();
                    $datos->cexterno    = $value->getDcoCexterno();
    				$datos->clavamano   = $value->getDcoClavamano();

    				$detalles[] = $datos;
    			}
    		}

    		// echo '<pre>';print_r($detalles);exit;

    		return $this->render('MantencionBundle::asignarBannos.html.twig', array(
                'defaultData'   => $defaultData->getAll(),
                'userData'      => $userData->getUserData(),
                'listaBannos'   => $listaBannos,
                'detalles' 	    => $detalles,
                'cliente'       => $nombreCliente,
                'id'            => $id
        		));

    	}else{
    		return $this->redirectToRoute('bodega_vista_nuevobanno');
    		exit;
    	}

    }

    public function guardarAsignacionAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

    	if( $request->getMethod() == 'POST' )
        {
        	// variables
        	$em 			= $this->getDoctrine()->getManager();
            $select_bannos 	= ($request->get('select_asignarbanno', false))? $request->get('select_asignarbanno'): array();
            $cantRegistros  = 0;
            $nombreCliente;
            $idVenta;
            $detalle        = '';

            // foranea
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => 2 ));
            $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => 30 ));

            foreach($select_bannos as $key => $value)
            {
            	// foraneas
	           	$detalleContrato = $em->getRepository('BaseBundle:DetalleContrato')->findOneBy(array('dcoIdPk' => $key));

                // cambiar a 0 el campo activo
                if( $activos = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $detalleContrato->getDcoIdPk())) )
                {
                    foreach($activos as $activo)
                    {
                        $activo->setDnnbActivo(0);
                        $em->persist($activo);
                    }

                    $em->flush();
                }

            	foreach($value as $value2)
            	{
                    $detalle .= $value2.', ';
                    $id_banno = eregi_replace("[a-zA-Z]","",$value2);
                    
                    // foraneas
                    $banno = $em->getRepository('BaseBundle:Bannos')->findOneBy(array('banIdPk' => $id_banno));

                    // registrar o actualizar
                    if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbDetcontratoFk' => $detalleContrato->getDcoIdPk(), 'dnnbBannoFk' => $banno->getBanIdPk() )))
                    {
                        $dnnb->setDnnbActivo(1);
                    }else{
                        $dnnb = new DetcontratoNnBanno();

                        $dnnb->setDnnbFecharegistro(new \DateTime(date("Y-m-d H:i:s")));
                        $dnnb->setDnnbActivo(1);
                        $dnnb->setDnnbBannoFk($banno);
                        $dnnb->setDnnbDetcontratoFk($detalleContrato);
                    }

	            	$em->persist($dnnb);
            		$em->flush();
                    
                    $cantRegistros ++;
                }

                $nombreCliente = $detalleContrato->getDcoVentaFk()->getVenClienteFk()->getCliNombre();
                $idVenta = $detalleContrato->getDcoVentaFk()->getVenIdPk();
            }

            // registrar en bitacora de ventas y logs de usuario
            $userData       = $this->get('service.user.data');
            $defaultText    = $this->get('service.default.text');

            // foraneas
            $fkVenta = $em->getRepository('BaseBundle:Venta')->findOneBy(array('venIdPk' => $idVenta));
            
            // bitacora venta
            $defaultText->setBitacoraVenta('asignar_bannos', array('usuario' => $userData->getUserData()->nombre.' '.$userData->getUserData()->apellido, 'cantidad' => $cantRegistros, 'detalle' => $detalle));
            // $bitV_asignarBannos = $defaultText->getBitacoraVenta($userData->getUserData()->nombre.' '.$userData->getUserData()->apellido);

            $bitacora = new Bitacora();
            $bitacora->setBitFecha(new \DateTime(date("Y-m-d H:i:s")));
            $bitacora->setBitDescripcion($defaultText->getBitacoraVenta('asignar_bannos'));
            $bitacora->setBitSucursalFk($fkSucursal);
            $bitacora->setBitVentaFk($fkVenta);
            $em->persist($bitacora);
            $em->flush();

            // log usuario
            $defaultText->setLogUsuario('asignar_bannos', array('cliente' => $nombreCliente, 'cantidad' => $cantRegistros, 'id' => $idVenta, 'detalle' => $detalle));
            // $logU_guardarVenta = $defaultText->getLogUsuario($input_nombre, $venta->getVenIdPk());

            $logUsu = new UsuarioLog();
            $logUsu->setUloFecha(new \DateTime(date("Y-m-d H:i:s")));
            $logUsu->setUloDescripcion($defaultText->getLogUsuario('asignar_bannos'));
            $logUsu->setUloUsuarioFk($fkUsuario);
            $logUsu->setUloSucursalFk($fkSucursal);
            $em->persist($logUsu);
            $em->flush();

            // print_r($select_bannos);
    	   $result = true; 
        }else{
           $result = false; 
        }
        // variables
 		
 		echo json_encode(array('result' => $result));
 		exit;
    }

}
