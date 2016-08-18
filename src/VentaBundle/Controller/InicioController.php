<?php

namespace VentaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
/**
VISTAS
**/
    public function inicioAction()
    {
    	// validar session y permisos
        if(!$this->get('service.user.data')->ValidarSession('Arriendos')){return $this->redirectToRoute('base_vista_ingreso');}

    	// servicios
    	$defaultData = $this->get('service.default.data');
    	$defaultData->setHtmlHeader(array('title' => 'Inicio'));
    	$userData = $this->get('service.user.data');

        return $this->render('VentaBundle::inicio.html.twig', array(
        	'defaultData'   => $defaultData->getAll(),
        	'userData' 		=> $userData->getUserData(),
            'menuActivo'    => 'inicio'
        	));
    }
/**
FUNCIONES AJAX
*/
}
