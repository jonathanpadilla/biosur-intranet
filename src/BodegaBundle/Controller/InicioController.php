<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class InicioController extends Controller
{
    public function inicioAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Lista de baños'));
        $userData = $this->get('service.user.data');

        return $this->render('BodegaBundle::inicio.html.twig', array(
            'defaultData' => $defaultData->getAll(),
            'userData'    => $userData->getUserData(),
            'menuActivo'  => 'inicio'
        ));
    }
}