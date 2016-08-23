<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Endroid\QrCode\QrCode;

class VerBannoController extends Controller
{
    public function verBannoAction($id)
    {
    	// validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
    	$defaultData = $this->get('service.default.data');
    	$defaultData->setHtmlHeader(array('title' => 'Ver baño'));
    	$userData = $this->get('service.user.data');

        return $this->render('BodegaBundle::verBanno.html.twig', array(
        	'defaultData' => $defaultData->getAll(),
        	'userData' 		=> $userData->getUserData(),
        	'message' => '00000012'
        ));
    }
}
