<?php

namespace ClienteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class ListaClienteController extends Controller
{

/**
VISTAS
*/
    public function listaClienteAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Clientes')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Lista de Clientes'));
        $userData = $this->get('service.user.data');
        
        return $this->render('ClienteBundle::lista_cliente.html.twig', array(
            'defaultData' => $defaultData->getAll(),
            'userData'      => $userData->getUserData()
            ));
    }

/**
FUNCIONES AJAX
*/
	public function cargarListaAction(Request $request)
	{
		 // validar session
        if(!$this->get('service.user.data')->ValidarSession('Clientes')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $em     		= $this->getDoctrine()->getManager();
        $qb     		= $em->createQueryBuilder();
        $result 		= false;
        $listaClientes 	= '';

        // servicios
        $userData = $this->get('service.user.data');

         $q  = $qb->select(array('c'))
            ->from('BaseBundle:Cliente', 'c')
            ->where('c.cliSucursalFk = '.$userData->getUserData()->sucursalActiva)
            // ->orderBy('b.banIdPk', 'ASC')
            ->getQuery();

        if($resultQuery = $q->getResult())
        {
        	foreach($resultQuery as $value)
        	{
        		$listaClientes .= '<tr>';
        		$listaClientes .= '<td>'.$value->getCliIdPk().'</td>';
        		$listaClientes .= '<td>'.$value->getCliNombre().'</td>';
        		$listaClientes .= '<td>'.$value->getCliGiro().'</td>';
        		$listaClientes .= '<td>'.$value->getCliDireccion().', '.$value->getCliComunaFk()->getComNombre().'</td>';
        		$listaClientes .= '<td></td>';
        		$listaClientes .= '</tr>';
        	}

        	$result = true;
        }

        echo json_encode(array('result' => $result, 'lista_cliente' => $listaClientes));
        exit;
	}
}
