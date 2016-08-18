<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\Bannos;
use \stdClass;

class NuevoBannoController extends Controller
{
    public function nuevoBannoAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

    	// variables
    	$em         = $this->getDoctrine()->getManager();
        $qb         = $em->createQueryBuilder();
        $tipoBannos = array();
        $id_banno   = 0;
        $sucursal   = '';

    	// servicios
    	$defaultData = $this->get('service.default.data');
    	$defaultData->setHtmlHeader(array('title' => 'Nuevo baño'));
        $userData = $this->get('service.user.data');

        if( $tipoBanno = $em->getRepository('BaseBundle:BannosTipo')->findBy(array('btiSucursalFk' => $userData->getUserData()->sucursalActiva, 'btiActivo' => 1)) )
        {
            foreach($tipoBanno as $value)
            {
                $datos = new stdClass();

                $datos->id = $value->getBtiIdPk();
                $datos->nombre = $value->getBtiNombre();

                $tipoBannos[] = $datos;
            }
        }

        // obtener el ultimo id registrado
        $q  = $qb->select(array('MAX(b.banIdPk)'))
                    ->from('BaseBundle:Bannos', 'b')
                    ->orderBy('b.banIdPk', 'ASC')
                    ->getQuery();

        if($resultQuery = $q->getResult())
        {
            foreach($resultQuery as $value)
            {
                $id_banno = ($value[1])? str_pad($value[1]+1, 7, '0', STR_PAD_LEFT): '0000001';
            }
        }

        // obtener nombre de sucursal activa
        foreach($userData->getUserData()->sucursales as $value)
        {
            if($value->sucursalId == $userData->getUserData()->sucursalActiva)
            {
                $sucursal = $value->sucursalNombre;
            }
        }

        // echo '<pre>';print_r($userData->getUserData());exit;

        return $this->render('BodegaBundle::nuevoBanno.html.twig', array(
        	'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
            'tipoBannos'    => $tipoBannos,
        	'message'       => $id_banno,
            'sucursal'      => $sucursal,
            'menuActivo'    => 'agregarbannos'
        	));
    }

/**
FUNCIONES AJAX
*/
    public function guardarBannoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $result = false;

        // servicios
        $userData = $this->get('service.user.data');
        
        if( $request->getMethod() == 'POST' )
        {
            // variables
            $select_producto        = ($request->get('select_producto', false))     ?$request->get('select_producto')      :null;
            $select_tipoproducto    = ($request->get('select_tipoproducto', false)) ?$request->get('select_tipoproducto')   :null;
            $textarea_comentario    = ($request->get('textarea_comentario', false)) ?$request->get('textarea_comentario')   :null;
            $input_id               = ($request->get('input_id', false))            ?$request->get('input_id')              :null;
            $em                     = $this->getDoctrine()->getManager();
            $key                    = crypt($input_id, '$2a$07$jpdeveloperstringforsalt$');

            // print_r($select_producto);exit;
            // claves foraneas
            $fkTipoBanno    = $em->getRepository('BaseBundle:BannosTipo')->findOneBy(array('btiIdPk' => $select_tipoproducto));
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva));

            $banno = new Bannos();

            $banno->setBanTipo($select_producto);
            $banno->setBanSucursalFk($fkSucursal);
            $banno->setBanTipo2($fkTipoBanno);
            $banno->setBanFecharegistro(new \DateTime(date("Y-m-d H:i:s")));
            $banno->setBanComentario($textarea_comentario);
            $banno->setBanAsignado(0);
            $banno->setBanKey($key);
            $em->persist($banno);
            $em->flush();

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function cargarTipoBannoAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}
        
        // variables
        $select     = ($request->get('select', false))? $request->get('select', false): false;
        $em         = $this->getDoctrine()->getManager();
        $options    = '';

        $tipoBanno = $em->getRepository('BaseBundle:BannosTipo')->findBy(array('btiProducto' => $request->get('producto', false), 'btiActivo' => 1, 'btiSucursalFk' => 2));

        if($tipoBanno)
        {
            foreach($tipoBanno as $value)
            {
                $options .= '<option value="'.$value->getBtiIdPk().'" '.(($select)? 'selected="selected"': '').'>'.$value->getBtiNombre().'</option>';
            }

            $result = true;
        }else{
            $result = false;
        }

        echo json_encode(array('result' => $result, 'options' => $options));
        exit;
    }
}
