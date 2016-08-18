<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InicioController extends Controller
{
    public function inicioAction()
    {
    	// validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

    	// servicios
    	$defaultData = $this->get('service.default.data');
    	$defaultData->setHtmlHeader(array('title' => 'Inicio'));
    	$userData = $this->get('service.user.data');

        // echo '<pre>';print_r($userData);exit;

        return $this->render('BaseBundle::inicio.html.twig', array(
        	'defaultData' 	=> $defaultData->getAll(),
        	'userData' 		=> $userData->getUserData()
        	));
    }
}