<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\ProductoMovimiento;
use BaseBundle\Entity\Producto;
use \stdClass;

class ImprimirSeguimientoController extends Controller
{
    public function inicioAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData    = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Rutas'));
        $userData       = $this->get('service.user.data');

        $now = new \DateTime();
        $fecha = $now->format('d/m/Y');

        return $this->render('MantencionBundle::imprimirSeguimiento.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'fecha'         => $fecha,
            'userData'      => $userData->getUserData()
        ));
	}

/**
AJAX
**/
    public function cargarFormulariosAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        date_default_timezone_set('America/Santiago');

        // variables
        $listaFormularios   = array();
        $em                 = $this->getDoctrine()->getManager();
        $dia                = ($request->get('dia', false))? $request->get('dia'): null;
        $fecha              = ($request->get('fecha', false))? $request->get('fecha'): null;

        if($productos = $em->getRepository('BaseBundle:Ruta')->findBy(array('rutDia' => $dia, 'rutActivo' => 1 )))
        {
            foreach($productos as $value)
            {

                $fono = '';
                $qb = $em->createQueryBuilder();

                $q  = $qb->select(array('t'))
                    ->from('BaseBundle:Contacto', 't')
                    ->where('t.conClienteFk ='.$value->getRutDetallecontratoFk()->getDcoVentaFk()->getVenClienteFk()->getCliIdPk().' AND (t.conTipoFk = 1 OR t.conTipoFk = 2 )' )
                    ->getQuery();

                if($resultQuery = $q->getResult())
                {
                    foreach($resultQuery as $value2)
                    {
                        $fono = $value2->getConDetalle();
                    }
                }

                // fecha
                // $fecha = date('d/m/Y');

                $datos = new stdClass();

                $datos->id                  = $value->getRutIdPk();
                $datos->empresaNombre       = $value->getRutDetallecontratoFk()->getDcoVentaFk()->getVenClienteFk()->getCliNombre();
                $datos->servicio1           = '';
                $datos->servicio2           = '';
                $datos->servicio3           = '';
                $datos->servicio4           = $value->getRutDetallecontratoFk()->getDcoCbano();
                $datos->servicio5           = '';
                $datos->servicio6           = '';
                $datos->servicio7           = '';
                $datos->servicio8           = '';
                $datos->servicio9           = '';
                $datos->servicio10          = '';
                $datos->servicio11          = '';
                $datos->direccion           = $value->getRutDetallecontratoFk()->getDcoDireccion();
                $datos->fono                = $fono;
                $datos->comuna              = $value->getRutDetallecontratoFk()->getDcoComunaFk()->getComNombre();
                $datos->nombreConductor     = $value->getRutCamionFk()->getCamUsuarioFk()->getUsuNombre().' '.$value->getRutCamionFk()->getCamUsuarioFk()->getUsuApellido();
                $datos->patente             = $value->getRutCamionFk()->getCamPatente();
                $datos->firma               = '';
                $datos->nombreResponsable   = '';
                $datos->fecha               = $fecha;
                $datos->hora                = '';
                $datos->firma2              = '';
                $datos->observacionCliente  = '';
                $datos->horarioServicio     = '';
                $datos->cantidadM3          = '';
                $datos->cantidadLitro       = '';
                $datos->observacionBiosur   = '';
                $datos->insumo1             = $value->getRutDetallecontratoFk()->getDcoPapel();
                $datos->insumo2             = $value->getRutDetallecontratoFk()->getDcoSachet();

                $listaFormularios[] = $datos;

            }

        }

        // echo '<pre>';print_r($listaFormularios);exit;

        return $this->render('MantencionBundle:plantillas:formularios.html.twig', array(
            'listaFormularios' => $listaFormularios
            ));
    }

    public function mantencionDiariaAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        $form = ($request->get('form', false))? $request->get('form'): array();

        $informe = array();
        $total_insumo1 = 0;
        $total_insumo2 = 0;
        $total_insumo3 = 0;
        $total_insumo4 = 0;
        $total_insumo5 = 0;
        foreach($form as $value)
        {
            if(!isset($informe['chofer'][$value['input_nombre_conductor']]))
            {
                // echo $value['input_nombre_conductor'].' - ';
                $informe['chofer'][$value['input_nombre_conductor']]['papel']       = 0;
                $informe['chofer'][$value['input_nombre_conductor']]['sachet1']     = 0;
                $informe['chofer'][$value['input_nombre_conductor']]['sachet2']     = 0;
                $informe['chofer'][$value['input_nombre_conductor']]['jabon']       = 0;
                $informe['chofer'][$value['input_nombre_conductor']]['alcohol']     = 0;
            }


            $informe['chofer'][$value['input_nombre_conductor']]['papel']      += $value['insumo1'];
            $informe['chofer'][$value['input_nombre_conductor']]['sachet1']    += $value['insumo2'];
            $informe['chofer'][$value['input_nombre_conductor']]['sachet2']    += $value['insumo3'];
            $informe['chofer'][$value['input_nombre_conductor']]['jabon']      += $value['insumo4'];
            $informe['chofer'][$value['input_nombre_conductor']]['alcohol']    += $value['insumo5'];

            $total_insumo1 += $value['insumo1'];
            $total_insumo2 += $value['insumo2'];
            $total_insumo3 += $value['insumo3'];
            $total_insumo4 += $value['insumo4'];
            $total_insumo5 += $value['insumo5'];
        }

        $informe['total'] = array(
            'papel' => $total_insumo1,
            'sachet1' => $total_insumo2,
            'sachet2' => $total_insumo3,
            'jabon' => $total_insumo4,
            'alcohol' => $total_insumo5,
            );

        // echo '<pre>';print_r($informe);exit;
        $this->agregarStockInsumo(1, $total_insumo1, 'Insumos para mntención diaria');
        $this->agregarStockInsumo(2, $total_insumo2, 'Insumos para mntención diaria');
        $this->agregarStockInsumo(3, $total_insumo4, 'Insumos para mntención diaria');
        $this->agregarStockInsumo(4, $total_insumo5, 'Insumos para mntención diaria');

        return $this->render('MantencionBundle:Plantillas:imprimir_ruta_semanal.html.twig', array(
                'formulario'    => $form,
                'informe'       => json_encode($informe)
            ));

    }

    public function mantencionDiariaPdfAction(Request $request)
    {
        $informe = ($request->get('json', false))? $request->get('json'): array();

        // $choferes   = json_decode($informe);

        // echo '<pre>';print_r(json_decode($informe, true));exit;

        $html = $this->renderView('MantencionBundle:Plantillas:imprimir_ruta_semanal_pdf.html.twig', array(
            'informe' => json_decode($informe, true)
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
                    'title'=> 'Informe de Mantencion',
                    'header-right'=>'',
                    'header-font-size'=>0,
                    )),
                    200,
                array(
                    'Content-Type'          =>'/',
                    'Content-Disposition'   => 'attachment; filename="Informe de Mantencion.pdf"',
                )
            );

        return $response;

        // return $this->render('MantencionBundle:Plantillas:imprimir_ruta_semanal_pdf.html.twig', array(
        // //     'informe' => json_decode($informe)
        // ));
    }

    private function sumaFecha($fecha,$dia)
    {
        list($day,$mon,$year) = explode('/',$fecha);
        return date('d/m/Y',mktime(0,0,0,$mon,$day+$dia,$year));        
    }

    private function agregarStockInsumo($producto, $cantidad, $comentario)
    {
        $em = $this->getDoctrine()->getManager();
        $userData = $this->get('service.user.data');
        // foraneas
        $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));
        $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $userData->getUserData()->id ));
        $fkProducto = $em->getRepository('BaseBundle:Producto')->findOneBy(array('proIdPk' => $producto ));

        $movimiento_producto = new ProductoMovimiento();

        $movimiento_producto->setPmoTipo(2);
        $movimiento_producto->setPmoCantidad($cantidad);;
        $movimiento_producto->setPmoDetalle($comentario);
        $movimiento_producto->setPmoFecha(new \DateTime(date("Y-m-d H:i:s")));
        $movimiento_producto->setPmoSucursalFk($fkSucursal);
        $movimiento_producto->setPmoUsuarioFk($fkUsuario);
        $movimiento_producto->setPmoProductoFk($fkProducto);
        $em->persist($movimiento_producto);

        if($movimiento_producto)
        {
            $cant = $fkProducto->getProCantidad();

            $fkProducto->setProCantidad($cant - $cantidad);
            $em->persist($fkProducto);

            $result = true;
        }
        $em->flush();
    }
}