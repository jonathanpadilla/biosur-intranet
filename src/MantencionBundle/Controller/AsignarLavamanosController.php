<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\DetcontratoNnBanno;
use BaseBundle\Entity\Bitacora;
use BaseBundle\Entity\UsuarioLog;
use \stdClass;

class AsignarLavamanosController extends Controller
{
    public function asignarLavamanosAction(Request $request, $id)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

    	// variables
    	$em     = $this->getDoctrine()->getManager();
        $bannos = array();

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Inicio'));
        $userData = $this->get('service.user.data');

        // obtener productos del arriendo
        if($detalle = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $id)) )
        {
            foreach($detalle as $value)
            {
                if($productos = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value->getDcoIdPk(), 'dnnbActivo' => 1)))
                {
                    foreach($productos as $value)
                    {   
                        // seleccionar solo baños
                        if($value->getDnnbBannoFk()->getBanTipo() == 1)
                        {
                            $datos = new stdClass();

                            $datos->id              = $value->getDnnbIdPk();
                            $datos->candado         = $value->getDnnbCandado();
                            $datos->banno_id        = str_pad($value->getDnnbBannoFk()->getBanIdPk(), 7, '0', STR_PAD_LEFT);
                            $datos->banno_tipo      = $value->getDnnbBannoFk()->getBanTipo2()->getBtiNombre();
                            $datos->banno_lavamanos = ($value->getDnnbLavamanoFk())? str_pad($value->getDnnbLavamanoFk()->getBanIdPk(), 7, '0', STR_PAD_LEFT):0;

                            $bannos[] = $datos;
                        }

                    }
                }

            }
        }

        // echo '<pre>';print_r($bannos);exit;

        return $this->render('MantencionBundle::asignarLavamanos.html.twig', array(
                    'defaultData'   => $defaultData->getAll(),
                    'userData'      => $userData->getUserData(),
                    'id'            => $id,
                    'bannos'        => $bannos
                    ));

    }

/**
AJAX
**/
    public function guardarCandadoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $id         = ($request->get('id', false))? $request->get('id'): null;
        $candado    = ($request->get('candado', false))? $request->get('candado'): 0;
        $result     = false;

        if($id)
        {
            $em = $this->getDoctrine()->getManager();

            if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbIdPk' => $id)))
            {

                $dnnb->setDnnbCandado($candado);
                $em->persist($dnnb);
                $em->flush();

                $result = true;
            }

        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function asignacionLavamanosAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $id             = ($request->get('id', false))          ?$request->get('id')        :null;
        $lavamanos      = ($request->get('lavamanos', false))   ?$request->get('lavamanos') :null;
        $contrato       = ($request->get('contrato', false))    ?$request->get('contrato')  :null;
        $banno          = ($request->get('banno', false))       ?$request->get('banno')     :null;
        $result         = false;
        $lista          = array();
        $cargarLista    = '';

        if($id)
        {
            $em = $this->getDoctrine()->getManager();
            // cargar lista
            if($detalle = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $contrato)))
            {
                foreach($detalle as $value)
                {
                    if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value->getDcoIdPk(), 'dnnbActivo' => 1)))
                    {
                        foreach($dnnb as $value2)
                        {
                            // obtener lavamanos
                            if($value2->getDnnbBannoFk()->getBanTipo() == 5)
                            {
                                $datos = new stdClass();

                                $datos->id              = $value2->getDnnbIdPk();
                                $datos->lavamano_id     = $value2->getDnnbBannoFk()->getBanIdPk();
                                $datos->lavamano_tipo   = $value2->getDnnbBannoFk()->getBanTipo();
                                $datos->banno_asignado  = 0;

                                //revisar si esta asignado
                                if($detalle2 = $em->getRepository('BaseBundle:DetalleContrato')->findBy(array('dcoVentaFk' => $contrato)))
                                {
                                    foreach($detalle2 as $value3)
                                    {
                                        if($dnnb2 = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbDetcontratoFk' => $value3->getDcoIdPk(), 'dnnbActivo' => 1)))
                                        {
                                            foreach($dnnb2 as $value4)
                                            {
                                                if($value4->getDnnbBannoFk()->getBanTipo() == 1 && $value4->getDnnbLavamanoFk())
                                                {
                                                    if($value4->getDnnbLavamanoFk()->getBanIdPk() == $value2->getDnnbBannoFk()->getBanIdPk())
                                                    {
                                                        $datos->banno_asignado = $value4->getDnnbBannoFk()->getBanIdPk(); 
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $lista[] = $datos;

                            }

                        }

                        $result = true;
                    }
                }
            }

            // print_r($lista);exit;

            foreach($lista as $key => $value)
            {
                $num = $key + 1;

                $cargarLista .= '<tr><td class="fs15 fw600">'.$num.'.</td>';
                $cargarLista .= '<td>L'.str_pad($value->lavamano_id, 7, '0', STR_PAD_LEFT).'</td>';
                $cargarLista .= '<td class="text-right">';
                $cargarLista .= '<button type="button" class="btn btn-'.(($value->banno_asignado == $banno)? 'info': (($value->banno_asignado != 0)? 'warning':'success' ) ).' btn-sm fs12 button_asignar" data-id="'.$value->id.'" data-lavamano="'.$value->lavamano_id.'" '.(($value->banno_asignado != $banno)? (($value->banno_asignado != 0)? 'disabled':'' ): '' ).'>'.(($value->banno_asignado != 0)? 'Seleccionado':'Seleccionar').'</button>';
                $cargarLista .= '</td></tr>';
            }
        }

        // echo $cargarLista;
        // print_r($lista);
        // exit;

        echo json_encode(array('result' => $result, 'cargarLista' => $cargarLista));
        exit;
    }

    public function guardarAsignacionLavamanosAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $id         = ($request->get('id', false))? $request->get('id'): null;
        $lavamanos  = ($request->get('lavamano', false))? $request->get('lavamano'): null;
        $result     = false;
        $userData   = $this->get('service.user.data');

        if($id)
        {
            $em = $this->getDoctrine()->getManager();
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));

            if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbIdPk' => $id)))
            {
                if($fkLavamanos = $em->getRepository('BaseBundle:Bannos')->findOneBy(array('banIdPk' => $lavamanos)))
                {
                    $dnnb->setDnnbLavamanoFk($fkLavamanos);
                    $em->persist($dnnb);
                    $em->flush();

                    $idVenta = $dnnb->getDnnbDetcontratoFk()->getDcoVentaFk()->getVenIdPk();
                    $fkVenta = $em->getRepository('BaseBundle:Venta')->findOneBy(array('venIdPk' => $idVenta ));
                    $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => 30 ));

                    // registrar en bitacora de ventas y logs de usuario
                    // bitacora venta
                    $defaultText    = $this->get('service.default.text');
                    $defaultText->setBitacoraVenta('asignar_lavamanos', array('lavamanos' => str_pad($fkLavamanos->getBanIdPk(),7, '0', STR_PAD_LEFT), 'banno' => str_pad($dnnb->getDnnbBannoFk()->getBanIdPk(),7, '0', STR_PAD_LEFT), 'usuario' => $userData->getUserData()->nombre.' '.$userData->getUserData()->apellido ));

                    $bitacora = new Bitacora();
                    $bitacora->setBitFecha(new \DateTime(date("Y-m-d H:i:s")));
                    $bitacora->setBitDescripcion($defaultText->getBitacoraVenta('asignar_lavamanos'));
                    $bitacora->setBitSucursalFk($fkSucursal);
                    $bitacora->setBitVentaFk($fkVenta);
                    $em->persist($bitacora);
                    $em->flush();

                    // log usuario
                    $defaultText->setLogUsuario('asignar_lavamanos', array('lavamanos' => str_pad($fkLavamanos->getBanIdPk(),7, '0', STR_PAD_LEFT), 'banno' => str_pad($dnnb->getDnnbBannoFk()->getBanIdPk(),7, '0', STR_PAD_LEFT), 'cliente' => $fkVenta->getVenClienteFk()->getCliNombre(), 'id' => $fkVenta->getVenIdPk() ));

                    $logUsu = new UsuarioLog();
                    $logUsu->setUloFecha(new \DateTime(date("Y-m-d H:i:s")));
                    $logUsu->setUloDescripcion($defaultText->getLogUsuario('asignar_lavamanos'));
                    $logUsu->setUloUsuarioFk($fkUsuario);
                    $logUsu->setUloSucursalFk($fkSucursal);
                    $em->persist($logUsu);
                    $em->flush();

                    $result = true;
                }
            }
        }

        echo json_encode(array('result' => $result, 'lavamanos' => 'L'.str_pad($lavamanos, 7, '0', STR_PAD_LEFT) ));
        exit;
    }

    public function eliminarAsignacionLavamanosAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $result = false;
        $id     = ($request->get('id', false))? $request->get('id'): null;

        if($id)
        {
            $em = $this->getDoctrine()->getManager();

            if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbIdPk' => $id)))
            {
                $dnnb->setDnnbLavamanoFk(null);
                $em->persist($dnnb);
                $em->flush();

                $result = true;

            }
        }

        echo json_encode(array('result' => $result));
        exit;
    }
}