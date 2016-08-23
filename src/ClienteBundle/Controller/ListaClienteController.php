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

	}
}
