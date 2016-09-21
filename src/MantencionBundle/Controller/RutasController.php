<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use \stdClass;

class RutasController extends Controller
{
    public function rutasAction()
    {
        // validar session y permisos
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData    = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Rutas'));
        $userData       = $this->get('service.user.data');

        // variables
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();
        $listaDestinos  = array();

        // cargar tablas
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

                                // $listaDestinos[$value3->getRutDia()]['cliente_id']      = $value->getVenClienteFk()->getCliIdPk();
                                $dia        = $value3->getRutDia();
                                $cliente    = $value->getVenClienteFk()->getCliIdPk();
                                $id  = $value2->getDcoIdPk();
                                $productos  = $value2->getDcoCbano() + $value2->getDcoCcaseta() + $value2->getDcoCducha() + $value2->getDcoCexterno() + $value2->getDcoClavamano();

                                $listaDestinos[$dia][$cliente][$id]['venta_cliente']         = $value->getVenClienteFk()->getCliNombre();

                                $listaDestinos[$dia][$cliente][$id]['detalle_direccion']     = $value2->getDcoDireccion();
                                $listaDestinos[$dia][$cliente][$id]['detalle_ciudad']        = $value2->getDcoComunaFk()->getComNombre();
                                $listaDestinos[$dia][$cliente][$id]['detalle_productos']     = $productos;
                                $listaDestinos[$dia][$cliente][$id]['detalle_pbano']         = $value2->getDcoCbano();
                                $listaDestinos[$dia][$cliente][$id]['detalle_pcaseta']       = $value2->getDcoCcaseta();
                                $listaDestinos[$dia][$cliente][$id]['detalle_pducha']        = $value2->getDcoCducha();
                                $listaDestinos[$dia][$cliente][$id]['detalle_pexterno']      = $value2->getDcoCexterno();
                                $listaDestinos[$dia][$cliente][$id]['detalle_plavamano']     = $value2->getDcoClavamano();

                                $listaDestinos[$dia][$cliente][$id]['ruta_patente']          = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamPatente():null;
                                $listaDestinos[$dia][$cliente][$id]['ruta_chofer_nombre']    = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamUsuarioFk()->getUsuNombre():null;
                                $listaDestinos[$dia][$cliente][$id]['ruta_chofer_apellido']  = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamUsuarioFk()->getUsuApellido():null;
                                
                            }
                        }
                    }
                }
            }

        }

        // echo '<pre>';print_r($listaDestinos);exit;

        return $this->render('MantencionBundle::rutas.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
            'listaDestinos' => $listaDestinos
        ));

    }

    public function pdfRutaSemanalAction()
    {
        // validar session y permisos
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();
        $listaDestinos  = array();

        // cargar datos
        $q  = $qb->select(array('v'))
            ->from('BaseBundle:Venta', 'v')
            ->where('v.venFinalizado = 1')
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

                                // $listaDestinos[$value3->getRutDia()]['cliente_id']      = $value->getVenClienteFk()->getCliIdPk();
                                $dia        = $value3->getRutDia();
                                $cliente    = $value->getVenClienteFk()->getCliIdPk();
                                $productos  = $value2->getDcoCbano() + $value2->getDcoCcaseta() + $value2->getDcoCducha() + $value2->getDcoCexterno() + $value2->getDcoClavamano();

                                $listaDestinos[$dia][$cliente]['venta_cliente']         = $value->getVenClienteFk()->getCliNombre();

                                $listaDestinos[$dia][$cliente]['detalle_direccion']     = $value2->getDcoDireccion();
                                $listaDestinos[$dia][$cliente]['detalle_ciudad']        = $value2->getDcoComunaFk()->getComNombre();
                                $listaDestinos[$dia][$cliente]['detalle_productos']     = $productos;
                                $listaDestinos[$dia][$cliente]['detalle_pbano']         = $value2->getDcoCbano();
                                $listaDestinos[$dia][$cliente]['detalle_pcaseta']       = $value2->getDcoCcaseta();
                                $listaDestinos[$dia][$cliente]['detalle_pducha']        = $value2->getDcoCducha();
                                $listaDestinos[$dia][$cliente]['detalle_pexterno']      = $value2->getDcoCexterno();
                                $listaDestinos[$dia][$cliente]['detalle_plavamano']     = $value2->getDcoClavamano();

                                $listaDestinos[$dia][$cliente]['ruta_patente']          = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamPatente():null;
                                $listaDestinos[$dia][$cliente]['ruta_chofer_nombre']    = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamUsuarioFk()->getUsuNombre():null;
                                $listaDestinos[$dia][$cliente]['ruta_chofer_apellido']  = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamUsuarioFk()->getUsuApellido():null;
                                
                            }
                        }
                    }
                }
            }

        }

        // exportar a pdf
        $html =$this->renderView('MantencionBundle:Plantillas:ruta_semanal.html.twig', array(
            'listaDestinos' => $listaDestinos
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
                    'title'=> 'Limipieza semanal',
                    'header-right'=>'',
                    'header-font-size'=>0,
                    )),
                    200,
                array(
                    'Content-Type'          =>'/',
                    'Content-Disposition'   => 'attachment; filename="Limipieza_semanal.pdf"',
                )
            );

        // echo $html;
        // exit;

        return $response;
    }

    public function excelRutaSemanalAction()
    {
        // validar session y permisos
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();
        $listaDestinos  = array();

        // cargar datos
        $q  = $qb->select(array('v'))
            ->from('BaseBundle:Venta', 'v')
            ->where('v.venFinalizado = 1')
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

                                // $listaDestinos[$value3->getRutDia()]['cliente_id']      = $value->getVenClienteFk()->getCliIdPk();
                                $dia        = $value3->getRutDia();
                                $cliente    = $value->getVenClienteFk()->getCliIdPk();
                                $productos  = $value2->getDcoCbano() + $value2->getDcoCcaseta() + $value2->getDcoCducha() + $value2->getDcoCexterno() + $value2->getDcoClavamano();

                                $listaDestinos[$dia][$cliente]['venta_cliente']         = $value->getVenClienteFk()->getCliNombre();

                                $listaDestinos[$dia][$cliente]['detalle_direccion']     = $value2->getDcoDireccion();
                                $listaDestinos[$dia][$cliente]['detalle_ciudad']        = $value2->getDcoComunaFk()->getComNombre();
                                $listaDestinos[$dia][$cliente]['detalle_productos']     = $productos;
                                $listaDestinos[$dia][$cliente]['detalle_pbano']         = $value2->getDcoCbano();
                                $listaDestinos[$dia][$cliente]['detalle_pcaseta']       = $value2->getDcoCcaseta();
                                $listaDestinos[$dia][$cliente]['detalle_pducha']        = $value2->getDcoCducha();
                                $listaDestinos[$dia][$cliente]['detalle_pexterno']      = $value2->getDcoCexterno();
                                $listaDestinos[$dia][$cliente]['detalle_plavamano']     = $value2->getDcoClavamano();

                                $listaDestinos[$dia][$cliente]['ruta_patente']          = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamPatente():null;
                                $listaDestinos[$dia][$cliente]['ruta_chofer_nombre']    = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamUsuarioFk()->getUsuNombre():null;
                                $listaDestinos[$dia][$cliente]['ruta_chofer_apellido']  = ($value3->getRutCamionFk())?$value3->getRutCamionFk()->getCamUsuarioFk()->getUsuApellido():null;
                                
                            }
                        }
                    }
                }
            }

        }

        // exportar a excel
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()
            ->setCreator("Intranet Biosur")
            ->setLastModifiedBy("Intranet Biosur")
            ->setTitle("Limipieza semanal")
            ->setSubject("Limipieza semanal")
            ->setDescription("Limipieza semanal")
            ->setKeywords("ruta camion limpieza mantencion");

        $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Limipieza semanal');

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('B2', 'Nombre del cliente')
            ->setCellValue('C2', 'Dirección')
            ->setCellValue('D2', 'Cantidad de productos')
            ->setCellValue('E2', 'Chofer');

            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('B')
                ->setWidth(30);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('C')
                ->setWidth(25);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('D')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('E')
                ->setWidth(20);

        $row = 3;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('B'.$row, 'Nombre')
            ->setCellValue('C'.$row, 'direccion')
            ->setCellValue('D'.$row, 'cantidad de productos')
            ->setCellValue('E'.$row, 'chofer');

        $row++;

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Ruta semanal.xls'
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

        // echo 'excel!';
        // exit;
        
    }
}
