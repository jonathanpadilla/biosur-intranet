<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class ImprimirSeguimientoController extends Controller
{
    public function inicioAction()
    {

        // servicios
        $defaultData    = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Rutas'));
        $userData       = $this->get('service.user.data');

        return $this->render('MantencionBundle::imprimirSeguimiento.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData()
        ));
	}

/**
AJAX
**/
    public function cargarFormulariosAction(Request $request)
    {
        // variables
        $listaFormularios   = array();
        $em                 = $this->getDoctrine()->getManager();
        $dia                = ($request->get('dia', false))? $request->get('dia'): null;   

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

                $datos = new stdClass();

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
                $datos->fecha               = date('d/m/Y');
                $datos->hora                = '';
                $datos->firma2              = '';
                $datos->observacionCliente  = '';
                $datos->horarioServicio     = '';
                $datos->cantidadM3          = '';
                $datos->cantidadLitro       = '';
                $datos->observacionBiosur   = '';

                $listaFormularios[] = $datos;

            }

        }

        return $this->render('MantencionBundle:plantillas:formularios.html.twig', array(
            'listaFormularios' => $listaFormularios
            ));
    }

    public function mantencionDiariaAction()
    {
        // exportar a pdf
        // $html = $this->renderView('MantencionBundle:Plantillas:imprimir_ruta_semanal.html.twig');

        return $this->render('MantencionBundle:Plantillas:imprimir_ruta_semanal.html.twig');

        // $response = new Response (
        //     $this->get('knp_snappy.pdf')->getOutputFromHtml($html,
        //         array(
        //             'lowquality' => false,
        //             'print-media-type' => true,
        //             'encoding' => 'utf-8',
        //             'page-size' => 'Letter',
        //             'outline-depth' => 8,
        //             // 'orientation' => 'Portrait',
        //             'orientation' => 'Landscape',
        //             'title'=> 'Mantencion '.date('d-m-Y'),
        //             'header-right'=>'',
        //             'header-font-size'=>0,
        //             )),
        //             200,
        //         array(
        //             'Content-Type'          =>'/',
        //             'Content-Disposition'   => 'attachment; filename="Mantencion '.date('d-m-Y').'.pdf"',
        //         )
        //     );

        // // echo $html;
        // // exit;

        // return $response;
    }

    public function mantencionDiariaJsAction()
    {
        return $this->render('MantencionBundle:Plantillas:imprimir_ruta_semanal_js.html.twig');
    }
}