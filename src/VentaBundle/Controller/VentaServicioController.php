<?php

namespace VentaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\Cliente;
use BaseBundle\Entity\Contacto;
use BaseBundle\Entity\Venta;
use BaseBundle\Entity\DetalleContrato;
use BaseBundle\Entity\Ruta;
use BaseBundle\Entity\RutaDia;
use BaseBundle\Entity\Bitacora;
use BaseBundle\Entity\UsuarioLog;
use \stdClass;

class VentaServicioController extends Controller
{
/**
VISTAS
**/
    public function nuevaVentaAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

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

        // cargar clientes
        $q  = $qb->select(array('cl'))
            ->from('BaseBundle:Cliente', 'cl')
            ->getQuery();
        $resultQuery = $q->getResult();

        $listaClientes = array();
        if($resultQuery)
        {
            foreach($resultQuery as $value)
            {
                $datos = new stdClass();

                $datos->id      = $value->getCliIdPk();
                $datos->nombre  = $value->getCliNombre();

                $listaClientes[] = $datos;
            }
        }

        return $this->render('VentaBundle::nuevaVenta.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
        	'comunas'       => $listaComunas,
            'clientes'      => $listaClientes,
            'menuActivo'    => 'nuevaventa'
        	));
    }
/**
FUNCIONES AJAX
*/
	public function crearVentaAction(Request $request)
	{
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

		if( $request->getMethod() == 'POST' )
        {
            // variables
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();

        	// datos post
        	$select_cliente 		= ($request->get('select_cliente', false))?			$request->get('select_cliente'):				null;	
        	$input_nombre 			= ($request->get('input_nombre', false))?			ucfirst($request->get('input_nombre')):			null;
        	$input_rut 				= ($request->get('input_rut', false))?				$request->get('input_rut'):						null;
        	$input_giro 			= ($request->get('input_giro', false))?				ucfirst($request->get('input_giro')):			null;
        	$select_comuna 			= ($request->get('select_comuna', false))?			$request->get('select_comuna'):					null;
        	$input_direccion 		= ($request->get('input_direccion', false))?		ucfirst($request->get('input_direccion')):		null;
            $textarea_comentario    = ($request->get('textarea_comentario', false))?    ucfirst($request->get('textarea_comentario')):  null;
        	$comentario_detalle 	= ($request->get('comentario_detalle', false))?	    ucfirst($request->get('comentario_detalle')):	null;
            // arrays
            $datocontacto           = ($request->get('datocontacto', false))?           $request->get('datocontacto'):                  null;
        	$detalle 			    = ($request->get('detalle', false))?			    $request->get('detalle'):					    null;
            
            $datosVenta = array(
                'id_cliente'        => $select_cliente,
                'nombre_cliente'    => $input_nombre,
                'rut_cliente'       => $input_rut,
                'giro_cliente'      => $input_giro,
                'id_comuna'         => $select_comuna,
                'direccion_cliente' => $input_direccion,
                'comentario'        => $textarea_comentario,
                'comentario_detalle'=> $comentario_detalle,
                'contacto'          => $datocontacto,
                'detalle'           => $detalle
                );

            // echo '<pre>';print_r($datosVenta);exit;

        	// comuna, provincia y region
            $q  = $qb->select(array('c'))
                ->from('BaseBundle:Comuna', 'c')
                ->where('c.comIdPk = '.$select_comuna)
                ->getQuery();
            $resultQuery = $q->getResult();

            if($resultQuery)
            {
                foreach ($resultQuery as $value) {
                    $datosVenta['nombre_comuna']    = $value->getComNombre();
                    $datosVenta['nombre_provincia'] = $value->getComProvinciaFk()->getProNombre();
                    $datosVenta['nombre_region']    = $value->getComProvinciaFk()->getProRegionFk()->getRegNombre();
                }
            }


            // comuna, provincia y region de detalle, dias
            foreach($detalle as $key => $value)
            {
                // if($value['servicio'] != 'default')
                if($value['cantidadbano'] != '' || $value['cantidadcaseta'] != '' || $value['cantidadducha'] != '' || $value['cantidadexterno'] != '' || $value['cantidadlavamano'] != '')
                {

                    if( $ubicacion = $em->getRepository('BaseBundle:Comuna')->findOneBy(array('comIdPk' => $value['comuna'] )) )
                    {
                            $datosVenta['detalle'][$key]['nombre_comuna']    = $ubicacion->getComNombre();
                            $datosVenta['detalle'][$key]['nombre_provincia'] = $ubicacion->getComProvinciaFk()->getProNombre();
                            $datosVenta['detalle'][$key]['nombre_region']    = $ubicacion->getComProvinciaFk()->getProRegionFk()->getRegNombre();
                    }

                    // dias
                    // $dias = ((isset($value['dia1']))? 'Lunes':'').' '.((isset($value['dia2']))? 'Martes':'').' '.((isset($value['dia3']))? 'Miercoles':'').' '.((isset($value['dia4']))? 'Jueves':'').' '.((isset($value['dia5']))? 'Viernes':'').' '.((isset($value['dia6']))? 'Sabado':'').' '.((isset($value['dia7']))? 'Domingo':'');
                    $dias = '';
                    if(isset($value['dia']))
                    {
                        foreach($value['dia'] as $key2 => $value2)
                        {
                            $dias.= $key2.' ';
                            $listaDiasA = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
                            $listaDiasB = array(1, 2, 3, 4, 5, 6, 7);

                            $dias = str_replace($listaDiasB, $listaDiasA, $dias);
                        }
                    }
                        
                    $datosVenta['detalle'][$key]['dias'] = $dias;

                }
            }

        	$result = true;
        }else{
        	$result = false;
        }

		
		echo json_encode(array('result' => $result, 'info_venta' => $datosVenta));
		exit;
	}

    public function guardarVentaAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $userData = $this->get('service.user.data');
        
        if( $request->getMethod() == 'POST' )
        {
            // variables
            $em = $this->getDoctrine()->getManager();
            date_default_timezone_set('America/Santiago');

            // servicios

            // datos post
            $select_cliente         = ($request->get('select_cliente', false))?         $request->get('select_cliente'):                null;   
            $input_nombre           = ($request->get('input_nombre', false))?           ucwords($request->get('input_nombre')):         null;
            $input_rut              = ($request->get('input_rut', false))?              $request->get('input_rut'):                     null;
            $input_giro             = ($request->get('input_giro', false))?             ucfirst($request->get('input_giro')):           null;
            $select_comuna          = ($request->get('select_comuna', false))?          $request->get('select_comuna'):                 null;
            $input_direccion        = ($request->get('input_direccion', false))?        ucfirst($request->get('input_direccion')):      null;
            $textarea_comentario    = ($request->get('textarea_comentario', false))?    ucfirst($request->get('textarea_comentario')):  null;
            $comentario_detalle     = ($request->get('comentario_detalle', false))?     ucfirst($request->get('comentario_detalle')):   null;
            // arrays
            $datocontacto           = ($request->get('datocontacto', false))?           $request->get('datocontacto'):                  null;
            $detalle                = ($request->get('detalle', false))?                $request->get('detalle'):                       null;
            
            // validar campos requeridos
            $detalleVenta = false;
            foreach($detalle as $value)
            {
                // if($value['servicio'] != 'default')
                if($value['cantidadbano'] != '' || $value['cantidadcaseta'] != '' || $value['cantidadducha'] != '' || $value['cantidadexterno'] != '' || $value['cantidadlavamano'] != '')
                {
                    if($value['direccion'] != '' && isset($value['dia']))
                    {
                        $detalleVenta = true;
                    }else{
                        echo json_encode(array('result' => false));exit;
                    }
                }
            }

            if(!$detalleVenta)
            {
                echo json_encode(array('result' => false));exit;
            }

            $datosVenta = array(
                'id_cliente'        => $select_cliente,
                'nombre_cliente'    => $input_nombre,
                'rut_cliente'       => $input_rut,
                'giro_cliente'      => $input_giro,
                'id_comuna'         => $select_comuna,
                'direccion_cliente' => $input_direccion,
                'comentario'        => $textarea_comentario,
                'comentario_detalle'=> $comentario_detalle,
                'contacto'          => $datocontacto,
                'detalle'           => $detalle
                );

            // ld($datosVenta);exit;

            // claves foraneas
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));
            $fkComuna   = $em->getRepository('BaseBundle:Comuna')->findOneBy(array('comIdPk' => $datosVenta['id_comuna'] ));
            $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $userData->getUserData()->id ));

            // guardar o actualizar cliente
            $clienteAntiguo = false;
            if(!$cliente = $em->getRepository('BaseBundle:Cliente')->findOneBy(array('cliIdPk' => $datosVenta['id_cliente'] )))
            {
               $cliente = new Cliente();
               $cliente->setCliRut($datosVenta['rut_cliente']);
            }else{
                $clienteAntiguo = true;
            }

            $cliente->setCliSucursalFk($fkSucursal);
            $cliente->setCliComunaFk($fkComuna);
            $cliente->setCliUsuarioFk($fkUsuario);
            $cliente->setCliNombre($datosVenta['nombre_cliente']);
            $cliente->setCliGiro($datosVenta['giro_cliente']);
            $cliente->setCliFecharegistro(new \DateTime(date("Y-m-d H:i:s")));
            $cliente->setCliDireccion($datosVenta['direccion_cliente']);
            $cliente->setCliComentario($datosVenta['comentario']);
            $em->persist($cliente);
            $em->flush();

            // guardar contacto
            if(is_array($datosVenta['contacto']) && $cliente->getCliIdPk())
            {
                if($clienteAntiguo)
                {
                    $contactosAntiguos = $em->getRepository('BaseBundle:Contacto')->findByConClienteFk($cliente->getCliIdPk());

                    foreach ($contactosAntiguos as $contactoAntiguo) {
                        $em->remove($contactoAntiguo);
                    }
                    $em->flush();
                }

                foreach($datosVenta['contacto'] as $value)
                {
                    // claves foraneas
                    $fkTipo = $em->getRepository('BaseBundle:ContactoTipo')->findOneByCtiIdPk($value[1]);

                    $contactoCliente = new Contacto();
                    $contactoCliente->setConTipoFk($fkTipo);
                    $contactoCliente->setConClienteFk($cliente);
                    $contactoCliente->setConDetalle($value[2]);
                    $contactoCliente->setConNombrepersona(ucfirst($value[3]));
                    $em->persist($contactoCliente);
                    $em->flush();
                }
            }

            // guardar venta, detalle, ruta, dias
            if($cliente->getCliIdPk() && $detalleVenta)
            {
                // guardar venta
                $venta = new Venta();
                $venta->setVenClienteFk($cliente);
                $venta->setVenUsuarioFk($fkUsuario);
                $venta->setVenSucursalFk($fkSucursal);
                $venta->setVenTipo(1);
                $venta->setVenFechainicio(new \DateTime(date("Y-m-d H:i:s")));
                $venta->setVenComentario($datosVenta['comentario_detalle']);
                $venta->setVenFinalizado(1);
                $em->persist($venta);
                $em->flush();

                // guardar detalle venta
                if($venta->getVenIdPk())
                {
                    foreach($datosVenta['detalle'] as $value)
                    {
                        // if($value['servicio'] != 'default')
                        if($value['cantidadbano'] != '' || $value['cantidadcaseta'] != '' || $value['cantidadducha'] != '' || $value['cantidadexterno'] != '' || $value['cantidadlavamano'] != '')
                        {
                            // claves foraneas
                            $fkComuna   = $em->getRepository('BaseBundle:Comuna')->findOneBy(array('comIdPk' => $value['comuna'] ));
                            $fkServicio = $em->getRepository('BaseBundle:Servicio')->findOneBy(array('serIdPk' => 1 ));
                            
                            $detalle = new DetalleContrato();
                            $detalle->setDcoVentaFk($venta);
                            $detalle->setDcoComunaFk($fkComuna);
                            $detalle->setDcoServicioFk($fkServicio);
                            $detalle->setDcoDireccion($value['direccion']);
                            $detalle->setDcoCbano($value['cantidadbano']);
                            $detalle->setDcoCcaseta($value['cantidadcaseta']);
                            $detalle->setDcoCducha($value['cantidadducha']);
                            $detalle->setDcoCexterno($value['cantidadexterno']);
                            $detalle->setDcoClavamano($value['cantidadlavamano']);
                            
                            $detalle->setDcoNetobanno($value['netobano']);
                            $detalle->setDcoNetocaseta($value['netocaseta']);
                            $detalle->setDcoNetoducha($value['netoducha']);
                            $detalle->setDcoNetoexterno($value['netoexterno']);

                            $detalle->setDcoLat($value['lat']);
                            $detalle->setDcoLon($value['lon']);
                            $detalle->setDcoPapel($value['cantidadbano']);
                            $detalle->setDcoSachet($value['cantidadbano']);
                            $detalle->setDcoActivo(1);
                            $em->persist($detalle);
                            $em->flush();

                            // guardar ruta por cada venta registrada
                            if($detalle->getDcoIdPk())
                            {
                                foreach($value['dia'] as $key => $value2)
                                {
                                    $ruta = new Ruta();
                                    $ruta->setRutDetallecontratoFk($detalle);
                                    $ruta->setRutDia($key);
                                    $ruta->setRutActivo(1);
                                    $ruta->setRutFecharegistro(new \DateTime(date("Y-m-d H:i:s")));
                                    $ruta->setRutOrden(0);
                                    $em->persist($ruta);
                                    $em->flush();
                                }

                                if($ruta->getRutIdPk())
                                {

                                    $result     = true;
                                    $idventa    = $venta->getVenIdPk();
                                }

                            }

                        }

                    }
                }

                // registrar en bitacora de ventas y logs de usuario
                // bitacora venta
                $userData       = $this->get('service.user.data');
                $defaultText    = $this->get('service.default.text');
                $defaultText->setBitacoraVenta('guardar_venta', array('usuario' => $userData->getUserData()->nombre.' '.$userData->getUserData()->apellido));

                $bitacora = new Bitacora();
                $bitacora->setBitFecha(new \DateTime(date("Y-m-d H:i:s")));
                $bitacora->setBitDescripcion($defaultText->getBitacoraVenta('guardar_venta'));
                $bitacora->setBitSucursalFk($fkSucursal);
                $bitacora->setBitVentaFk($venta);
                $em->persist($bitacora);
                $em->flush();

                // log usuario
                $defaultText->setLogUsuario('guardar_venta', array('cliente' => $input_nombre, 'id' => $venta->getVenIdPk() ));

                $logUsu = new UsuarioLog();
                $logUsu->setUloFecha(new \DateTime(date("Y-m-d H:i:s")));
                $logUsu->setUloDescripcion($defaultText->getLogUsuario('guardar_venta'));
                $logUsu->setUloUsuarioFk($fkUsuario);
                $logUsu->setUloSucursalFk($fkSucursal);
                $em->persist($logUsu);
                $em->flush();

            }
            
            // $result     = false;
        }else{
            $result     = false;
            $idventa    = false;
        }

        echo json_encode(array('result' => $result, 'error' => 0, 'idventa' => $idventa));
        exit;
    }
}
