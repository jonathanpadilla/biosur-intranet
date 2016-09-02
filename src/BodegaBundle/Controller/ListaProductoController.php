<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\ProductoMovimiento;
use BaseBundle\Entity\Producto;
use \stdClass;

class ListaProductoController extends Controller
{
    public function listaProductoAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Nuevo baño'));
        $userData = $this->get('service.user.data');

    	// variables
    	$em = $this->getDoctrine()->getManager();
        $listaProductos = array();

        if($productos = $em->getRepository('BaseBundle:Producto')->findBy(array('proSucursal' => $userData->getUserData()->sucursalActiva )))
        {
            foreach($productos as $value)
            {
                $datos = new stdClass();

                $datos->id          = $value->getProIdPk();
                $datos->nombre      = $value->getProNombre();
                $datos->cantidad    = $value->getProCantidad();
                $datos->alertStock  = ($value->getProCantidad() <= 60 )? 1: 0;

                $listaProductos[] = $datos;
            }
        }

        return $this->render('BodegaBundle::listaProductos.html.twig', array(
        	'defaultData'       => $defaultData->getAll(),
            'userData'          => $userData->getUserData(),
            'listaProductos'    => $listaProductos,
            'menuActivo'        => 'listaproductos'
        	));
    }

/**
FUNCIONES AJAX
*/

    public function historialProductoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $result             = false;
        $em                 = $this->getDoctrine()->getManager();
        $qb                 = $em->createQueryBuilder();
        $id                 = ($request->get('id', false))? $request->get('id', false): false;
        $listaMovimiento    = '';

        // servicios
        $userData = $this->get('service.user.data');

        $q  = $qb->select(array('p'))
                    ->from('BaseBundle:ProductoMovimiento', 'p')
                    ->where('p.pmoProductoFk = '.$id)
                    ->setFirstResult( 0 )
                    ->setMaxResults( 10 )
                    ->orderBy('p.pmoIdPk', 'DESC')
                    ->getQuery();
        
        if($resultQuery = $q->getResult())
        {
            foreach($resultQuery as $value)
            {
                $listaMovimiento .= '<tr>';
                $listaMovimiento .= '<td>'.$value->getPmoFecha()->format('d/m/Y').'</td>';
                $listaMovimiento .= '<td>'.$value->getPmoUsuarioFk()->getUsuNombre().' '.$value->getPmoUsuarioFk()->getUsuApellido().'</td>';
                $listaMovimiento .= '<td>'.$value->getPmoCantidad().'</td>';
                $movimiento = ($value->getPmoTipo() == 1)? '<span class="label label-success">Ingreso</span>': '<span class="label label-warning">Salida</span>';
                $listaMovimiento .= '<td>'.$movimiento.' $'.number_format($value->getPmoPrecio(), 0, ',', '.' ).'</td>';
                $listaMovimiento .= '<td>'.$value->getPmoDetalle().'</td>';
                $listaMovimiento .= '</tr>';

            }

            $result = true;
        }

        echo json_encode(array('result' => $result, 'lista' => $listaMovimiento));
        exit;
    }
}
