<?php

namespace SucursalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CambiarSucursalController extends Controller
{
    public function cambiarSucursalAction(Request $request)
    {

    	// validar session
        if(!$this->get('service.user.data')->ValidarSession('Sucursales')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $id     = ($request->get('id', false))?$request->get('id'):null;
        $result = false;

        // servicios
        $userData = $this->get('service.user.data');
        $result = $userData->setSucursalActiva($id);

        // print_r($userData->getUserData());

        echo json_encode(array('result' => $result));
        exit;

    }
}