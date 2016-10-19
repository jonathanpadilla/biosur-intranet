<?php

namespace VentaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BaseBundle\Entity\DetalleContrato;
use BaseBundle\Entity\Ruta;
use \stdClass;

class VerVentaController extends Controller
{
/**
VISTAS
**/
    public function verAction($id)
    {
    	// validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

    	if($id)
    	{
    		// variables
    		$em 			= $this->getDoctrine()->getManager();
        	$qb 			= $em->createQueryBuilder();
        	$listaDiasA 	= array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
            $listaDiasB 	= array(1, 2, 3, 4, 5, 6, 7);
            $asignarRuta 	= 'success';
            $asignarBanno 	= 'success';
            $totalDias 		= 0;
            $bitacora 		= array();
            $listaBitacora  = array();

            // servicios
    		$defaultData 	= $this->get('service.default.data');
    		$defaultData->setHtmlHeader(array('title' => 'Inicio'));
    		$userData 		= $this->get('service.user.data');

        	// cargar venta cliente
	        $q  = $qb->select(array('v'))
	            ->from('BaseBundle:Venta', 'v')
	            ->where('v.venIdPk ='.$id)
	            ->getQuery();
	        $resultQuery = $q->getResult();

	        if($resultQuery)
	        {
	        	$datosCliente = array();
	        	foreach($resultQuery as $value)
	        	{
	        		$datosCliente['id_usuario'] 		= $value->getVenUsuarioFk()->getUsuIdPk();
	        		$datosCliente['nombre_usuario'] 	= $value->getVenUsuarioFk()->getUsuNombre();
	        		$datosCliente['apellido_usuario'] 	= $value->getVenUsuarioFk()->getUsuApellido();
	        		$datosCliente['id_venta'] 			= $value->getVenIdPk();
	        		$datosCliente['fecha_registro'] 	= $value->getVenFechainicio()->format('d/m/Y');
	        		$datosCliente['fecha_termino'] 		= ($value->getVenFechatermino())?$value->getVenFechatermino()->format('d/m/Y'):null;
	        		$datosCliente['comentario_venta']	= $value->getVenComentario();
	        		$tipo_pago_venta					= $value->getVenTipopago();
	        		$datosCliente['id_cliente'] 		= $value->getVenClienteFk()->getCliIdPk();
	        		$datosCliente['nombre_cliente'] 	= $value->getVenClienteFk()->getCliNombre();
	        		$datosCliente['cantidadTotal'] 		= 0;
	        		$datosCliente['cantidadDias'] 		= 0;

	        		$datosCliente['cbanos'] 			= 0;
	        		$datosCliente['ccaseta'] 			= 0;
	        		$datosCliente['cducha'] 			= 0;
	        		$datosCliente['cexterno'] 			= 0;
	        		$datosCliente['clavamano'] 			= 0;

	        		$datosCliente['asignacion'] 		= 0;
	        		$datosCliente['lavamanos'] 		    = 0;
	        	}

	        	switch ($tipo_pago_venta) {
	    			case 1: $datosCliente['tipo_pago_venta'] = 'Order de compra';
	    				break;
	    			case 2: $datosCliente['tipo_pago_venta'] = 'Efectivo';
	    				break;
	    			case 3: $datosCliente['tipo_pago_venta'] = 'Documento';
	    				break;
	    			case 4: $datosCliente['tipo_pago_venta'] = 'Transferencia';
	    				break;
	    			default: $datosCliente['tipo_pago_venta'] = 'Sin especificar';
	    				break;
	    		}

	        	// bitacora
	        	$q2  = $qb->select(array('b'))
		            ->from('BaseBundle:Bitacora', 'b')
		            ->where('b.bitVentaFk ='.$datosCliente['id_venta'])
		            ->orderBy('b.bitIdPk', 'DESC')
		            ->add('orderBy', 'b.bitIdPk DESC')
        			// ->setFirstResult(10)
		            // ->setMaxResults(10)
		            ->getQuery();
		            
		        $resultQuery2 = $q2->getResult();

		        foreach($resultQuery2 as $value)
		        {
		        	$datos = new stdClass();

		        	$datos->id 			= $value->getBitIdPk();
		        	$datos->fecha 		= $value->getBitFecha()->format('d/m/Y H:i:s');
		        	$datos->descripcion = $value->getBitDescripcion();

		        	$listaBitacora[] = $datos;
		        }

		        // echo '<pre>';print_r(array_reverse($listaBitacora));exit;

	        	// detalle de venta
	        	$detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $id, 'dcoActivo' => 1 ));

	        	if($detalleVenta)
	        	{
	        		$where = '';
	        		$mayor = 0;
	        		foreach($detalleVenta as $value)
	        		{
	        			$datos = new stdClass();

	        			$datos->id 					= $value->getDcoIdPk();
	        			$datos->direccion 			= $value->getDcoDireccion();
	        			$datos->lat 				= $value->getDcoLat();
	        			$datos->lon 				= $value->getDcoLon();
	        			$datos->cbano 				= $value->getDcoCbano();
	        			$datos->ccaseta				= $value->getDcoCcaseta();
	        			$datos->cducha 				= $value->getDcoCducha();
	        			$datos->cexterno			= $value->getDcoCexterno();
	        			$datos->clavamano			= $value->getDcoClavamano();
	        			$datos->comentario 			= $value->getDcoComentario();
	        			$datos->comuna 				= $value->getDcoComunaFk()->getComNombre();
	        			$datos->provincia 			= $value->getDcoComunaFk()->getComProvinciaFk()->getProNombre();
	        			$datos->region 				= $value->getDcoComunaFk()->getComProvinciaFk()->getProRegionFk()->getRegNombre();
	        			$datos->id_servicio 		= $value->getDcoServicioFk()->getSerIdPk();
	        			$datos->nombre_servicio 	= $value->getDcoServicioFk()->getSerNombre();
	        			$datos->sachet 				= $value->getDcoSachet();
	        			$datos->papel 				= $value->getDcoPapel();

	        			// asignacion de baños completa o sin asignar
	        			if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value->getDcoIdPk() )))
	        			{

	        				$countDnnb = 0;
	        				foreach($dnnb as $value2)
	        				{
	        					if($value2->getDnnbActivo() == 1)
	        					{
	        						$countDnnb ++;
	        					}

	        					// mostroar/ocultar boton agregar lavamanos
	        					if($value2->getDnnbActivo() == 1 && $value2->getDnnbBannoFk()->getBanTipo() == 1)
	        					{
	        						$datosCliente['asignacion'] = 1;
	        					}
	        				}

	        				$total = $value->getDcoCbano()+$value->getDcoCcaseta()+$value->getDcoCducha()+$value->getDcoCexterno()+$value->getDcoClavamano();

	        				if($countDnnb < $total)
	        				{
	        					$asignarBanno = 'warning';
	        					$datos->cantidadReal = $total;
	        					$datosCliente['cantidadTotal'] += $total;

	        					$datosCliente['cbanos'] 	+= $value->getDcoCbano();
				        		$datosCliente['ccaseta'] 	+= $value->getDcoCcaseta();
				        		$datosCliente['cducha'] 	+= $value->getDcoCducha();
				        		$datosCliente['cexterno'] 	+= $value->getDcoCexterno();
				        		$datosCliente['clavamano'] 	+= $value->getDcoClavamano();
	        				}else{
	        					$datos->cantidadReal = $countDnnb;

	        					$datosCliente['cbanos'] 	+= $value->getDcoCbano();
				        		$datosCliente['ccaseta'] 	+= $value->getDcoCcaseta();
				        		$datosCliente['cducha'] 	+= $value->getDcoCducha();
				        		$datosCliente['cexterno'] 	+= $value->getDcoCexterno();
				        		$datosCliente['clavamano'] 	+= $value->getDcoClavamano();

	        					$datosCliente['cantidadTotal'] += $countDnnb;
	        				}

	        				$datosCliente['asignarBanno'] = $asignarBanno;

	        			}else{

	        				$datosCliente['cbanos'] 	+= $value->getDcoCbano();
			        		$datosCliente['ccaseta'] 	+= $value->getDcoCcaseta();
			        		$datosCliente['cducha'] 	+= $value->getDcoCducha();
			        		$datosCliente['cexterno'] 	+= $value->getDcoCexterno();
			        		$datosCliente['clavamano'] 	+= $value->getDcoClavamano();

	        				$datos->cantidadReal = $value->getDcoCbano()+$value->getDcoCcaseta()+$value->getDcoCducha()+$value->getDcoCexterno()+$value->getDcoClavamano();

	        				$datosCliente['cantidadTotal'] 	+= $datosCliente['cbanos']+$datosCliente['ccaseta']+$datosCliente['cducha']+$datosCliente['cexterno']+$datosCliente['clavamano'];
	        				$datosCliente['asignarBanno'] 	= 'warning';
	        			}

	        			// cargar ruta de detalle de venta
	        			if($rutaVenta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $value->getDcoIdPk(), 'rutSolicitado' => null )))
	        			{
	        				foreach($rutaVenta as $value2)
	        				{
	        					$datos2 = new stdClass();
	        					$totalDias ++;
	        					
	        					// ruta completada o sin asignar
	        					if(!$value2->getRutCamionFk())
	        					{
	        						$asignarRuta = 'warning';
	        					}
	        					$datosCliente['asignarRuta'] = $asignarRuta;

	        					$datos2->id 		= $value2->getRutIdPk();
	        					$datos2->camion 	= ($value2->getRutCamionFk())?$value2->getRutCamionFk()->getCamIdPk():null;
	        					$datos2->dia 		= $value2->getRutDia();

	        					$datos2->nombre_dia = str_replace($listaDiasB, $listaDiasA, $datos2->dia);

	        					$datos->ruta[] = $datos2;
	        				}
	        			}

	        			if($value->getDcoActivo() == 1)
	        			{
	        				$datosCliente['detalle'][] = $datos;
	        			}else{
	        				$datosCliente['detalle'] = array();
	        			}

	        			$where .= ($mayor == 1)? ' OR ':'';
		        		$where .= 'm.manDetallecontratoFk = '.$value->getDcoIdPk();

		        		$mayor = 1;

	        		}
	        		$datosCliente['cantidadDias'] += $totalDias;
	        	}

	        	$q3  = $qb->select(array('m'))
		            ->from('BaseBundle:Mantencion', 'm')
		            ->where($where)
		            ->orderBy('m.manIdPk', 'DESC')
		            // ->add('orderBy', 'b.bitIdPk DESC')
        			// ->setFirstResult(0)
		         //    ->setMaxResults(50)
		            ->getQuery();

		       	$listaMantencion = array();
		        if($resultQuery3 = $q3->getResult())
		        {
		        	foreach($resultQuery3 as $value)
		        	{		        		
		        		$cant_bannos 	= ($value->getManRutaFk())?$value->getManRutaFk()->getRutDetallecontratoFk()->getDcoCbano(): 0;
		        		$cant_casetas 	= ($value->getManRutaFk())?$value->getManRutaFk()->getRutDetallecontratoFk()->getDcoCcaseta(): 0;
		        		$cant_duchas 	= ($value->getManRutaFk())?$value->getManRutaFk()->getRutDetallecontratoFk()->getDcoCducha(): 0;
		        		$cant_externos 	= ($value->getManRutaFk())?$value->getManRutaFk()->getRutDetallecontratoFk()->getDcoCexterno(): 0;

		        		$idDetalleContrato = $value->getManDetallecontratoFk()->getDcoIdPk();

			        	$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['planificado'] 		= ($value->getManRutaFk())?1:0;
			        	$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['fecha'] 			= $value->getManFecharegistro()->format('Y-m-d');
		        		$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['id_ruta'] 			= ($value->getManRutaFk())?$value->getManRutaFk()->getRutIdPk(): 0;
		        		$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['id_detalle'] 		= ($value->getManDetallecontratoFk())?$value->getManDetallecontratoFk()->getDcoIdPk(): 0;
		        		$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['cant_realizado']	= (!isset($listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['cant_realizado']))? 1: $listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['cant_realizado']+1;
		        		$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['cant_total'] 		= $cant_bannos+$cant_casetas+$cant_duchas+$cant_externos;
		        		
		        		if($value->getManRutaFk())
		        		{
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['descripcion'] 		= 'Limpieza baño quimico';
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['ciudad'] 			= ($value->getManRutaFk())?	$value->getManRutaFk()->getRutDetallecontratoFk()->getDcoComunaFk()->getComNombre(): 'no especificada';
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['direccion'] 		= ($value->getManRutaFk())?	$value->getManRutaFk()->getRutDetallecontratoFk()->getDcoDireccion(): 'no especificada';
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['nn_banno_id']		= '';
		        		}else{
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['descripcion'] 		= 'Limpieza baño quimico - no programado';
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['ciudad'] 			= ($value->getManNnbannoFk())?	$value->getManNnbannoFk()->getDnnbDetcontratoFk()->getDcoComunaFk()->getComNombre(): 'no especificada';
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['direccion'] 		= ($value->getManNnbannoFk())?	$value->getManNnbannoFk()->getDnnbDetcontratoFk()->getDcoDireccion(): 'no especificada';
		        			$listaMantencion[$value->getManFecharegistro()->format('d/m/Y').'_'.$idDetalleContrato]['nn_banno_id']		= ($value->getManNnbannoFk())? 	$value->getManNnbannoFk()->getDnnbIdPk():'';
		        		}

		        	}
		        }

	        	// echo '<pre>';print_r($datosCliente);exit;

    			return $this->render('VentaBundle::verVenta.html.twig', array(
    				'defaultData' 		=> $defaultData->getAll(),
    				'userData' 			=> $userData->getUserData(),
    				'datosCliente' 		=> $datosCliente,
    				'listaMantencion' 	=> array_slice($listaMantencion, 0, 10),
    				'bitacora'			=> array_slice($listaBitacora, 0, 7),
    				'menuActivo'    	=> 'listaventa'
    				));
	        }else{
	        	
    			return $this->redirectToRoute('venta_vista_listaventa');
	        }

    	}else{
    		return $this->redirectToRoute('venta_vista_listaventa');
    	}
    }
/**
FUNCIONES AJAX
*/
	public function detalleMantencionAction(Request $request)
	{
		// validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        $result = false;
        $em 	= $this->getDoctrine()->getManager();
        $qb 	= $em->createQueryBuilder();

    	$idruta 	= ($request->get('idruta', false))		?$request->get('idruta')	:null;
    	$iddetalle 	= ($request->get('iddetalle', false))	?$request->get('iddetalle')	:null;
    	$fecha 		= ($request->get('fecha', false))		?$request->get('fecha')		:null;

    	$q  = $qb->select(array('m'))
            ->from('BaseBundle:Mantencion', 'm')
            ->where('m.manDetallecontratoFk = '.$iddetalle)
            ->andWhere("m.manFecharegistro LIKE '".$fecha."%'")
            ->orderBy('m.manFecharegistro', 'ASC')
            ->getQuery();

        $listaMantencion = '';
        if($resultQuery = $q->getResult())
        {
        	foreach($resultQuery as $value)
        	{
        		// $datos = new stdClass();

        		$letra = '';
        		switch($value->getManNnbannoFk()->getDnnbBannoFk()->getBanTipo())
                {
                    case 1:
                        $letra  = 'B';
                        break;
                    case 2:
                        $letra  = 'C';
                        break;
                    case 3:
                        $letra  = 'D';
                        break;
                    case 4:
                        $letra  = 'E';
                        break;
                    case 5:
                        $letra  = 'L';
                        break;
                }

                $lavamano 	= ($value->getManNnbannoFk()->getDnnbLavamanoFk())?' L'.str_pad($value->getManNnbannoFk()->getDnnbLavamanoFk()->getBanIdPk(), 7, '0', STR_PAD_LEFT):'';
                $candado 	= ($value->getManNnbannoFk()->getDnnbCandado())? ' <i class="fa fa-unlock-alt fa-lg"></i>':'';

        		$listaMantencion .= '<tr>';
        		$listaMantencion .= '<td>'.$value->getManFecharegistro()->format('H:i:s').'</td>';
        		$listaMantencion .= '<td>'.( ($value->getManUsuarioFk()) ? $value->getManUsuarioFk()->getUsuNombre() : '' ).' '.( ($value->getManUsuarioFk()) ?$value->getManUsuarioFk()->getUsuApellido() : '' ).'</td>';
        		$listaMantencion .= '<td>'.$letra.str_pad($value->getManNnbannoFk()->getDnnbBannoFk()->getBanIdPk(), 7, '0', STR_PAD_LEFT).'</td>';
        		$listaMantencion .= '<td class="text-right">'.$lavamano.$candado.'</td>';
        		$listaMantencion .= '<td>'.$value->getManComentario().'</td>';
        		$listaMantencion .= '<td class="text-right"><div class="btn-group">';
        		//$listaMantencion .= '<button class="btn btn-default btn-sm"><i class="fa fa-picture-o"></i></button>';
        		$listaMantencion .= '<button class="btn btn-default btn-sm btn-ubicacion" data-lat="'.$value->getManLat().'" data-lng="'.$value->getManLng().'"><i class="fa fa-map-marker"></i></button>';
        		$listaMantencion .= '</div></td>';
        		$listaMantencion .= '</tr>';

        		// $datos->id 				= $value->getManIdPk();
        		// $datos->foto 			= $value->getManFoto();
        		// $datos->lat 			= $value->getManLat();
        		// $datos->lng 			= $value->getManLng();

        		// $datos->comentario 		= $value->getManComentario();
        		// $datos->realizado 		= $value->getManRealizado();
        		// // $datos->hora 			= $value->getManFecharegistro()->format('H:m:s');
        		// $datos->nn_idbano		= $value->getManNnbannoFk()->getDnnbBannoFk()->getBanIdPk();
        		// $datos->nn_idlavamanos	= ($value->getManNnbannoFk()->getDnnbLavamanoFk())?$value->getManNnbannoFk()->getDnnbLavamanoFk()->getBanIdPk():null;
        		// $datos->nn_candado		= ($value->getManNnbannoFk())?$value->getManNnbannoFk()->getDnnbCandado():null;
        		// $datos->usu_id			= $value->getManUsuarioFk()->getUsuIdPk();
        		// $datos->usu_nombre		= $value->getManUsuarioFk()->getUsuNombre();
        		// $datos->usu_apellido	= $value->getManUsuarioFk()->getUsuApellido();

        		// $listaMantencion[] = $datos;
        	}

        	$result = true;
        }

        // echo '<pre>';print_r($listaMantencion);

        echo json_encode(array('result' => $result, 'lista_mantencion' => $listaMantencion));
        exit;
	}

	public function contratoPdfAction($id)
	{

		setlocale(LC_ALL, "es_ES.UTF-8");
		$em 			= $this->getDoctrine()->getManager();
        $qb 			= $em->createQueryBuilder();
        $nombreCliente 	= '';
        $fecha 			= '';

        $q  = $qb->select(array('v'))
            ->from('BaseBundle:Venta', 'v')
            ->where('v.venIdPk ='.$id)
            ->getQuery();
	    
	    if($resultQuery = $q->getResult())
	    {
	    	$datos_arriendo = array();
	    	foreach($resultQuery as $value)
	    	{
	    		// meses español
	    		$mes = '';
	    		switch($value->getVenFechainicio()->format('m'))
	    		{
	    			case 1: $mes = 'Enero';break;
	    			case 2: $mes = 'Febrero';break;
	    			case 3: $mes = 'Marzo';break;
	    			case 4: $mes = 'Abril';break;
	    			case 5: $mes = 'Mayo';break;
	    			case 6: $mes = 'Junio';break;
	    			case 7: $mes = 'Julio';break;
	    			case 8: $mes = 'Agosto';break;
	    			case 9: $mes = 'Septiembre';break;
	    			case 10: $mes = 'Octubre';break;
	    			case 11: $mes = 'Noviembre';break;
	    			case 12: $mes = 'Diciembre';break;
	    		}

	    		$datos_arriendo['id_venta'] 			= $value->getVenIdPk();
	    		$datos_arriendo['id_usuario'] 			= $value->getVenUsuarioFk()->getUsuIdPk();
	    		$datos_arriendo['nombre_usuario'] 		= $value->getVenUsuarioFk()->getUsuNombre();
	    		$datos_arriendo['apellido_usuario'] 	= $value->getVenUsuarioFk()->getUsuApellido();
	    		$datos_arriendo['dia_venta']	 		= $value->getVenFechainicio()->format('d');
	    		$datos_arriendo['mes_venta']	 		= $mes;
	    		$datos_arriendo['anno_venta']	 		= $value->getVenFechainicio()->format('Y');
	    		$datos_arriendo['nombre_cliente']		= $value->getVenClienteFk()->getCliNombre();
	    		$datos_arriendo['rut_cliente']			= $value->getVenClienteFk()->getCliRut();
	    		$datos_arriendo['direccion_cliente']	= $value->getVenClienteFk()->getCliDireccion();
	    		$datos_arriendo['comuna_cliente']		= $value->getVenClienteFk()->getCliComunaFk()->getComNombre();

	    		// tipo pago
	    		switch ($value->getVenTipopago()) {
	    			case 1: $datos_arriendo['tipo_pago'] = 'Order de compra';
	    				break;
	    			case 2: $datos_arriendo['tipo_pago'] = 'Efectivo';
	    				break;
	    			case 3: $datos_arriendo['tipo_pago'] = 'Documento';
	    				break;
	    			case 4: $datos_arriendo['tipo_pago'] = 'Transferencia';
	    				break;
	    			default: $datos_arriendo['tipo_pago'] = 'Sin especificar';
	    				break;
	    		}

	    		$nombreCliente = $value->getVenClienteFk()->getCliNombre();
	    		$fecha = $value->getVenFechainicio()->format('d').' de '.$mes.' '.$value->getVenFechainicio()->format('Y');
	    	}
	    }
	    $dias = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);

	    $detalle_contrato = array();
	    if($detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $id, 'dcoActivo' => 1 )))
	    {

	    	foreach($detalleVenta as $value)
	    	{
	    		$datos = new stdClass();

	    		$nbano 		= ($value->getDcoCbano())? 'Baños: '.$value->getDcoCbano().' ':'';
	    		$ncaseta 	= ($value->getDcoCcaseta())? 'Casetas: '.$value->getDcoCcaseta().' ':'';
	    		$nducha 	= ($value->getDcoCducha())? 'Duchas: '.$value->getDcoCducha().' ':'';
	    		$nexterno 	= ($value->getDcoCexterno())? 'Externos: '.$value->getDcoCexterno():'';

	    		$vbano = ($value->getDcoNetobanno())?$value->getDcoNetobanno():0;
	    		$vcaseta = ($value->getDcoNetocaseta())?$value->getDcoNetocaseta():0;
	    		$vducha = ($value->getDcoNetoducha())?$value->getDcoNetoducha():0;
	    		$vexterno = ($value->getDcoNetoexterno())?$value->getDcoNetoexterno():0;

	    		$datos->cantidad = $nbano.$ncaseta.$nducha.$nexterno;
	    		$datos->valor = $vbano + $vcaseta + $vducha + $vexterno;
	    		$datos->ubicacion = $value->getDcoDireccion();

	    		$detalle_contrato[] = $datos;

	    		if($ruta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $value->getDcoIdPk() )))
	    		{
	    			foreach($ruta as $value2)
	    			{
	    				$dias[$value2->getRutDia()] = 1;
	    			}
	    		}
	    	}
	    }

	    $suma_dias = array_sum($dias);

	    // print_r($detalle_contrato);exit;

		// return $this->render('VentaBundle:Plantillas:contrato.html.twig',array(
		// 	'datos_arriendo' => $datos_arriendo,
		// 	'detalle_contrato' => $detalle_contrato,
		// 	'mantenciones' => $suma_dias
		// 	));
		$html = $this->renderView('VentaBundle:Plantillas:contrato.html.twig', array(
			'datos_arriendo' => $datos_arriendo,
			'detalle_contrato' => $detalle_contrato,
			'mantenciones' => $suma_dias
			));

        $response = new Response (
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html,
                array(
                    'lowquality' => false,
                    'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'Letter',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    // 'orientation' => 'Landscape',
                    'title'=> 'Contrato',
                    'header-right'=>'',
                    'header-font-size'=>0,
                    )),
                    200,
                array(
                    'Content-Type'          =>'/',
                    'Content-Disposition'   => 'attachment; filename="'.$nombreCliente.' - '.$fecha.'.pdf"',
                )
            );

        return $response;
	}

	public function guardarDetalleVentaAction(Request $request)
	{
		// validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        $result = false;

		if( $request->getMethod() == 'POST' )
		{
			$datos = $request->get('txt_datos', false);
        	$em = $this->getDoctrine()->getManager();

        	foreach($datos as $key => $value)
            {
                // buscar camion
                if($detalle = $em->getRepository('BaseBundle:DetalleContrato')->findOneBy(array('dcoIdPk' => $key)))
                {
                    $detalle->setDcoSachet($value['sachet']);
                    $detalle->setDcoPapel($value['papel']);
                    $em->persist($detalle);
                }

            }

            $em->flush();
            $result = true;

		}

		echo json_encode(array('result' => $result));
		exit;
	}

	public function guardarNombreClienteAction(Request $request)
	{
		$result = false;
		
		if( $request->getMethod() == 'POST' )
		{
			$em 	= $this->getDoctrine()->getManager();
			$nombre = $request->get('nombre', false);
			$id 	= $request->get('id', false);

			if($cliente = $em->getRepository('BaseBundle:Cliente')->findOneBy(array('cliIdPk' => $id)))
			{
				$cliente->setCliNombre($nombre);
                $em->persist($cliente);
                $em->flush();
			}
			
		}

		echo json_encode(array('result' => $result));
		exit;
	}

	public function crearDetalleArriendoAction(Request $request)
	{
		$result = false;

		if( $request->getMethod() == 'POST' )
		{
			$hide_id 				= $request->get('hide_id', false);
			$txt_cantidad_banno 	= $request->get('txt_cantidad_banno', false);
			$txt_cantidad_caseta 	= $request->get('txt_cantidad_caseta', false);
			$txt_cantidad_ducha 	= $request->get('txt_cantidad_ducha', false);
			$txt_cantidad_externo 	= $request->get('txt_cantidad_externo', false);
			$txt_cantidad_lavamano 	= $request->get('txt_cantidad_lavamano', false);
			$dia 					= $request->get('dia', false);
			$select_comuna 			= $request->get('select_comuna', false);
			$txt_direccion 			= $request->get('txt_direccion', false);

			if(is_numeric($hide_id) && is_array($dia))
			{
				$em = $this->getDoctrine()->getManager();

				// claves foraneas
				$fkVenta 	= $em->getRepository('BaseBundle:Venta')->findOneBy(array('venIdPk' => $hide_id));
				$fkComuna 	= $em->getRepository('BaseBundle:Comuna')->findOneBy(array('comIdPk' => $select_comuna));
				$fkServicio = $em->getRepository('BaseBundle:Servicio')->findOneBy(array('serIdPk' => 1));

				// guardar detalle
				$detalle = new DetalleContrato();
				$detalle->setDcoVentaFk($fkVenta);
				$detalle->setDcoComunaFk($fkComuna);
				$detalle->setDcoServicioFk($fkServicio);
				$detalle->setDcoDireccion($txt_direccion);
				$detalle->setDcoCbano($txt_cantidad_banno);
				$detalle->setDcoCcaseta($txt_cantidad_caseta);
				$detalle->setDcoCducha($txt_cantidad_ducha);
				$detalle->setDcoCexterno($txt_cantidad_externo);
				$detalle->setDcoClavamano($txt_cantidad_lavamano);
				$detalle->setDcoSachet($txt_cantidad_banno);
				$detalle->setDcoPapel($txt_cantidad_banno);
				$detalle->setDcoActivo(1);

				$em->persist($detalle);
                $em->flush();

                // guardar ruta
                if($detalle->getDcoIdPk())
                {
                    foreach($dia as $key => $value)
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

                    $result = true;

                }

			}


		}

		echo json_encode(array('result' => $result));
		exit;
	}

	public function cargarComunasAction()
	{
		$em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $result = false;

		// cargar comunas
        $q  = $qb->select(array('c'))
            ->from('BaseBundle:Comuna', 'c')
            ->getQuery();
        $resultQuery = $q->getResult();

        $listaComunas = '';
        if($resultQuery)
        {
        	foreach($resultQuery as $value)
        	{
        		$listaComunas .= '<option value="'.$value->getComIdPk().'">'.$value->getComNombre().'</option>';
        	}

        	$result = true;

        }

        echo json_encode(array('result' => $result, 'listaComunas' => $listaComunas));
        exit;

	}

	public function eliminarDetalleAction(request $request)
	{
		$result = false;

		if( $request->getMethod() == 'POST' )
		{
			$em = $this->getDoctrine()->getManager();
			$id = $request->get('id', false);

			if(is_numeric($id) && $detalle = $em->getRepository('BaseBundle:DetalleContrato')->findOneBy(array('dcoIdPk' => $id)))
			{

				$detalle->setDcoActivo(0);
				$em->persist($detalle);

				// obtener ruta para finalizarla
                if($ruta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $detalle )))
                {
                    foreach($ruta as $value)
                    {
                        $value->setRutActivo(0);
                        $em->persist($value);
                    }
                }

                // obtener contrato nn baño para finalizarlo
                if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $detalle )))
                {
                    foreach($dnnb as $value2)
                    {
                        $value2->setDnnbActivo(0);
                        $em->persist($value2);
                    }
                }
                
                $em->flush();
			}

			$result = true;
		}

		echo json_encode(array('result' => $result));
		exit;
	}
}