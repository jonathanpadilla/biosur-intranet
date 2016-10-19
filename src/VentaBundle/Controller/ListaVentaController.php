<?php

namespace VentaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;
use \PHPExcel_Style_Fill;
use \PHPExcel_Style_Border;

class ListaVentaController extends Controller
{
/**
VISTAS
**/
    public function listaAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Inicio'));
        $userData = $this->get('service.user.data');
        
        return $this->render('VentaBundle::listaVenta.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
            'menuActivo'    => 'listaventa'
            ));
    }
/**
FUNCIONES AJAX
*/
	public function cargarListaAction()
	{
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $userData = $this->get('service.user.data');
        
		// variables
		$em     = $this->getDoctrine()->getManager();
        $qb     = $em->createQueryBuilder();
        $where	= 'v.venSucursalFk = '.$userData->getUserData()->sucursalActiva.' AND v.venFinalizado = 1';

        // cargar lista
        $q  = $qb->select(array('v'))
                    ->from('BaseBundle:Venta', 'v')
                    ->where($where)
                    ->orderBy('v.venIdPk', 'DESC')
                    // ->setFirstResult( $pagina )
                    // ->setMaxResults( $limite )
                    ->getQuery();
        $resultQuery = $q->getResult();

        if($resultQuery)
        {
        	$cargarLista = '';
        	foreach($resultQuery as $value)
        	{
                // botones
                $bottonRuta = '';
                $bottonBannos = '';

                // detalle venta
                $detDireccion = '';
                $detServicio  = '';
                $detCantidad  = 0;
                if($detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $value->getVenIdPk(), 'dcoActivo' => 1 )))
                {
                    foreach($detalleVenta as $value2)
                    {
                        // total de productos
                        $total = $value2->getDcoCbano()+$value2->getDcoCcaseta()+$value2->getDcoCducha()+$value2->getDcoCexterno()+$value2->getDcoClavamano();
                        // asignacion de baños completa o sin asignar
                        if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value2->getDcoIdPk() )))
                        {
                            $countDnnb = 0;
                            foreach($dnnb as $value3)
                            {
                                if($value3->getDnnbActivo() == 1)
                                {
                                    $countDnnb ++;
                                }
                            }


                            if($countDnnb < $total)
                            {
                                $bottonBannos = '<a href="'.$this->get('router')->generate('mantencion_vista_asignarbannos', array('id' => $value->getVenIdPk() )).'" class="btn btn-sm btn-warning pulsate" data-toggle="tooltip" data-container="body" data-original-title="Asignar baños"><i class="fa fa-check-circle-o"></i></a>';
                            }

                        }else{
                            $bottonBannos = '<a href="'.$this->get('router')->generate('mantencion_vista_asignarbannos', array('id' => $value->getVenIdPk() )).'" class="btn btn-sm btn-warning pulsate" data-toggle="tooltip" data-container="body" data-original-title="Asignar baños"><i class="fa fa-check-circle-o"></i></a>';
                        }

                        // cargar datos detalle
                        $detServicio    = '<p>'.$value2->getDcoServicioFk()->getSerNombre().'</p>';
                        $detDireccion   .= '<p>'.$value2->getDcoDireccion().', '.$value2->getDcoComunaFk()->getComNombre().', '.$value2->getDcoComunaFk()->getComProvinciaFk()->getProNombre().', '.$value2->getDcoComunaFk()->getComProvinciaFk()->getProRegionFk()->getRegNombre().'</p>';
                        $detCantidad    += $total;

                        // botones de ruta y asignacion de baños
                        if($rutaDetalle = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $value2->getDcoIdPk())))
                        {
                            foreach($rutaDetalle as $value3)
                            {
                                // asignacion de camiones completa o incompleta
                                if(!$value3->getRutCamionFk())
                                {
                                    $bottonRuta = '<a href="'.$this->get('router')->generate('mantencion_vista_agregarruta', array('id' => $value->getVenIdPk() )).'" class="btn btn-sm btn-warning pulsate" data-toggle="tooltip" data-container="body" data-original-title="Agregar a la ruta"><i class="fa fa-road"></i></a>';
                                }
                            }
                        }


	        		}
        		}

        		// crear fila
        		$cargarLista.= '<tr>';
        		$cargarLista.= '<td>'.$value->getVenFechainicio()->format('d/m/Y').'</td>';
        		$cargarLista.= '<td>'.$value->getVenClienteFk()->getCliNombre().'</td>';
        		$cargarLista.= '<td>'.$detServicio.'</td>';
        		$cargarLista.= '<td>'.$detDireccion.'</td>';
        		$cargarLista.= '<td>'.$detCantidad.' Productos</td>';

        		// botones de operaciones
        		$cargarLista.= '<td class="text-right"><div class="btn-group">';
        		// $cargarLista.= '<a href="#" class="btn btn-sm btn-warning pulsate" data-toggle="tooltip" data-container="body" data-original-title="Asignar baños"><i class="fa fa-check-circle-o"></i></a>';
                $cargarLista.= $bottonBannos;
        		$cargarLista.= $bottonRuta;
        		$cargarLista.= '<a href="'.$this->get('router')->generate('venta_vista_verventa', array('id' => $value->getVenIdPk() )).'" class="btn btn-sm btn-default"><i class="fa fa-search"></i></a>';
        		$cargarLista.= '<button type="button" data-id="'.$value->getVenIdPk().'" class="btn btn-sm btn-danger button_eliminarVenta"><i class="fa fa-power-off"></i></button>';
        		$cargarLista.= '</div></td>';
        		$cargarLista.= '</tr>';

        	}

        }else{
            $cargarLista = false;
        }

		$result = true;

		echo json_encode(array('result' => $result, 'cargarlista' => $cargarLista));
		exit;
	}

    public function finalizarVentaAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        $result = false;

        if( $request->getMethod() == 'POST' )
        {
            $id = ($request->get('id', false))? $request->get('id'): null;
            $em = $this->getDoctrine()->getManager();

            // obtener venta para finalizarlo
            if($venta = $em->getRepository('BaseBundle:Venta')->findOneBy(array('venIdPk' => $id )))
            {
                $venta->setVenFinalizado(0);
                $em->persist($venta);

                if($detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $id )))
                {
                    foreach($detalleVenta as $value)
                    {
                        // obtener contrato nn baño para finalizarlo
                        if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value->getDcoIdPk() )))
                        {
                            foreach($dnnb as $value2)
                            {
                                $value2->setDnnbActivo(0);
                                $em->persist($value2);
                            }
                        }

                        // obtener ruta para finalizarla
                        if($ruta = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDetallecontratoFk' => $value->getDcoIdPk() )))
                        {
                            foreach($ruta as $value3)
                            {
                                $value3->setRutActivo(0);
                                $em->persist($value3);
                            }
                        }
                    }
                }

                $em->flush();

                $result = true;
            }
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function exportarListaClienteExcelAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $userData = $this->get('service.user.data');
        
        // variables
        $em     = $this->getDoctrine()->getManager();
        $qb     = $em->createQueryBuilder();
        $where  = 'v.venSucursalFk = '.$userData->getUserData()->sucursalActiva.' AND v.venFinalizado = 1';

        $now = new \DateTime();

        // cargar lista
        $q  = $qb->select(array('v'))
                    ->from('BaseBundle:Venta', 'v')
                    ->where($where)
                    ->orderBy('v.venIdPk', 'DESC')
                    // ->setFirstResult( $pagina )
                    // ->setMaxResults( $limite )
                    ->getQuery();

        if($resultQuery = $q->getResult())
        {
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator("BQ700")
                ->setLastModifiedBy("BQ700")
                ->setTitle("INVENTARIO DE BAÑOS QUÍMICOS")
                ->setSubject("INVENTARIO")
                ->setDescription("LISTA DE INVENTARIO DE BAÑOS QUÍMICOS")
                ->setKeywords("bq700 lista baños quimicos inventario");

            $phpExcelObject->setActiveSheetIndex(0);
            $phpExcelObject->getActiveSheet()->setTitle('INVENTARIO DE BAÑOS QUÍMICOS');

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A1', 'INVENTARIO DE BAÑOS QUÍMICOS')
                ->setCellValue('B1', 'FECHA: '.$now->format('d/m/Y'))

                ->setCellValue('A3', 'UBICACIÓN DE BAÑOS QUÍMICOS')
                ->setCellValue('B3', 'CANTIDAD')

                ->setCellValue('A4', 'EMPRESA')
                ->setCellValue('B4', 'B')
                ->setCellValue('C4', 'C')
                ->setCellValue('D4', 'D')
                ->setCellValue('E4', 'E');

            // tamaño celda
            $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(60);
            $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(6);
            $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(6);
            $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(6);
            $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(6);

            // combinar celdas
            $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B1:E1');
            $phpExcelObject->setActiveSheetIndex(0)->mergeCells('B3:E3');

            // bordes
            $phpExcelObject->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("B3")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("B4")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("C4")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("D4")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("E4")->getFont()->setBold(true);

            // $phpExcelObject->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            // $phpExcelObject->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('ea661c');


            $row = 5;
            $total_clientes = 0;
            $total_banos    = 0;
            $total_casetas  = 0;
            $total_duchas   = 0;
            $total_externos = 0;
            foreach($resultQuery as $value)
            {
                if($detalleVenta = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $value->getVenIdPk(), 'dcoActivo' => 1 )))
                {
                    $banos      = 0;
                    $casetas    = 0;
                    $duchas     = 0;
                    $externos   = 0;

                    foreach($detalleVenta as $value2)
                    {
                        $banos      += $value2->getDcoCbano();
                        $casetas    += $value2->getDcoCcaseta();
                        $duchas     += $value2->getDcoCducha();
                        $externos   += $value2->getDcoCexterno();
                        
                    }
                }

                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row, $value->getVenClienteFk()->getCliNombre())
                    ->setCellValue('B'.$row, (($banos)?$banos:''))
                    ->setCellValue('C'.$row, (($casetas)?$casetas:''))
                    ->setCellValue('D'.$row, (($duchas)?$duchas:''))
                    ->setCellValue('E'.$row, (($externos)?$externos:''));

                $row ++;
                $total_clientes ++;
                $total_banos    += $banos;
                $total_casetas  += $casetas;
                $total_duchas   += $duchas;
                $total_externos += $externos;

            }

            $fin = $row - 1;
            // borde tabla
            $phpExcelObject->getActiveSheet()->getStyle('A3:E'.$fin)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            // informacion final
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('G3', 'TOTAL ARRIENDOS')->setCellValue('H3', $total_clientes)
                ->setCellValue('G5', 'TOTAL BAÑOS')->setCellValue('H5', $total_banos)
                ->setCellValue('G6', 'TOTAL CASETAS')->setCellValue('H6', $total_casetas)
                ->setCellValue('G7', 'TOTAL DUCHAS')->setCellValue('H7', $total_duchas)
                ->setCellValue('G8', 'TOTAL EXTERNOS')->setCellValue('H8', $total_externos);

            // ESTILOS INFORMACION FINAL
            $phpExcelObject->getActiveSheet()->getStyle("G3")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("G5")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("G6")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("G7")->getFont()->setBold(true);
            $phpExcelObject->getActiveSheet()->getStyle("G8")->getFont()->setBold(true);
            $phpExcelObject->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(30);
            $phpExcelObject->getActiveSheet()->getStyle('G3:H3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $phpExcelObject->getActiveSheet()->getStyle('G5:H5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $phpExcelObject->getActiveSheet()->getStyle('G6:H6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $phpExcelObject->getActiveSheet()->getStyle('G7:H7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $phpExcelObject->getActiveSheet()->getStyle('G8:H8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');

            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'Inventario - '.$now->format('d-m-Y').'.xls'
            );

            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);

            return $response;

        }

    }
}
