<?php

namespace PluginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SelectsController extends Controller
{
    public function tipoContactosAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	// cargar contactos
    	$tipoContactos = $em->getRepository('BaseBundle:ContactoTipo')
                            ->findAll();

        $options = '';
        foreach($tipoContactos as $value)
        {
        	$options.= '<option value="'.$value->getCtiIdPk().'">'.$value->getCtiNombre().'</option>';
        }
    	
    	// print_r($contactos);

    	echo json_encode(array('result' => true, 'options' => $options));
        exit;
    }
}