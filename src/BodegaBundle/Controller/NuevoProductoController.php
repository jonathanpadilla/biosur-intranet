<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\ProductoMovimiento;
use BaseBundle\Entity\Producto;
use \stdClass;

class NuevoProductoController extends Controller
{
    public function nuevoProductoAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

    	// variables
    	$em         = $this->getDoctrine()->getManager();
        $sucursal   = '';

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Nuevo baño'));
        $userData = $this->get('service.user.data');

        // if( $tipoBanno = $em->getRepository('BaseBundle:BannosTipo')->findBy(array('btiSucursalFk' => $sucursal, 'btiActivo' => 1)) )

        // obtener nombre de sucursal activa
        foreach($userData->getUserData()->sucursales as $value)
        {
            if($value->sucursalId == $userData->getUserData()->sucursalActiva)
            {
                $sucursal = $value->sucursalNombre;
            }
        }

        return $this->render('BodegaBundle::nuevoProducto.html.twig', array(
        	'defaultData'   => $defaultData->getAll(),
            'sucursal'      => $sucursal,
            'userData'      => $userData->getUserData(),
            'menuActivo'    => 'agregarproducto'
        	));
    }

/**
FUNCIONES AJAX
*/

    public function listaProductosAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        $result     = false;
        $em         = $this->getDoctrine()->getManager();
        $userData   = $this->get('service.user.data');
        $select     = '';

        if( $productos = $em->getRepository('BaseBundle:Producto')->findBy(array('proSucursal' => $userData->getUserData()->sucursalActiva, 'proActivo' => 1 )) )
        {
            foreach($productos as $value)
            {
                $select .= '<option value="'.$value->getProIdPk().'">'.$value->getProNombre().'</option>';
            }

            $result = true;
        }

        echo json_encode(array('result' => $result, 'select' => $select));
        exit;
    }

    public function guardarProductoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $result = false;
        $nombre = ($request->get('nombre', false))? $request->get('nombre', false): false;
        $em     = $this->getDoctrine()->getManager();

        // servicios
        $userData = $this->get('service.user.data');

        if($nombre)
        {
            // foraneas
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));

            $producto = new Producto();

            $producto->setProNombre($nombre);
            $producto->setProCantidad(0);
            $producto->setProSucursal($fkSucursal);
            $producto->setProActivo(1);
            $em->persist($producto);
            $em->flush();

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function guardarStockProductoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}
        
        // variables
        $result     = false;
        $producto   = ($request->get('producto', false))? $request->get('producto', false): false;
        $cantidad   = ($request->get('cantidad', false))? $request->get('cantidad', false): false;
        $precio     = ($request->get('input_precio', false))? $request->get('input_precio', false): 0;
        $comentario = ($request->get('comentario', false))? $request->get('comentario', false): false;
        $em         = $this->getDoctrine()->getManager();

        // servicios
        $userData = $this->get('service.user.data');

        if($producto && $cantidad)
        {
            // foraneas
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));
            $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $userData->getUserData()->id ));
            $fkProducto = $em->getRepository('BaseBundle:Producto')->findOneBy(array('proIdPk' => $producto ));

            $movimiento_producto = new ProductoMovimiento();

            $movimiento_producto->setPmoTipo(1);
            $movimiento_producto->setPmoCantidad($cantidad);
            $movimiento_producto->setPmoPrecio($precio);
            $movimiento_producto->setPmoDetalle($comentario);
            $movimiento_producto->setPmoFecha(new \DateTime(date("Y-m-d H:i:s")));
            $movimiento_producto->setPmoSucursalFk($fkSucursal);
            $movimiento_producto->setPmoUsuarioFk($fkUsuario);
            $movimiento_producto->setPmoProductoFk($fkProducto);
            $em->persist($movimiento_producto);

            if($movimiento_producto)
            {
                $cant = $fkProducto->getProCantidad();

                $fkProducto->setProCantidad($cant + $cantidad);
                $em->persist($fkProducto);

                $result = true;
            }
            $em->flush();
            
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function salidaProductoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $result     = false;
        $id   = ($request->get('id', false))? $request->get('id', false): false;
        $cantidad   = ($request->get('cantidad', false))? $request->get('cantidad', false): false;
        $comentario = ($request->get('comentario', false))? $request->get('comentario', false): false;
        $em         = $this->getDoctrine()->getManager();

        // servicios
        $userData = $this->get('service.user.data');

        if($cantidad)
        {
            // foraneas
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));
            $fkUsuario  = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $userData->getUserData()->id ));
            $fkProducto = $em->getRepository('BaseBundle:Producto')->findOneBy(array('proIdPk' => $id ));

            $movimiento_producto = new ProductoMovimiento();

            $movimiento_producto->setPmoTipo(2);
            $movimiento_producto->setPmoCantidad($cantidad);
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

        echo json_encode(array('result' => $result));
        exit;
    }
}
