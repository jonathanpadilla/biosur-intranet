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

        // $porcentajes = array(
        //     'bannos'    => $porcentaje_bannos
        // );

        // print_r($porcentaje_bannos);exit;
        $bannos     = $this->porcentajeProductos(1);
        $casetas    = $this->porcentajeProductos(2);
        $duchas     = $this->porcentajeProductos(3);
        $lavamanos  = $this->porcentajeProductos(5);

        return $this->render('BaseBundle::inicio.html.twig', array(
        	'defaultData' 	=> $defaultData->getAll(),
            'bannos'        => $bannos,
            'casetas'       => $casetas,
            'duchas'        => $duchas,
            'lavamanos'     => $lavamanos,
        	'userData' 		=> $userData->getUserData()
        	));
    }

    private function porcentajeProductos($tipo)
    {
        // servicios
        $userData = $this->get('service.user.data');

        // variables
        $em          = $this->getDoctrine()->getManager();
        $total       = 0;
        $arrendados  = 0;
        $porcentaje  = 0;
        $result      = array(
                        'porcentaje'    => 0,
                        'arrendados'    => 0
                    );

        if($banno = $em->getRepository('BaseBundle:Bannos')->findBy(array('banSucursalFk' => $userData->getUserData()->sucursalActiva, 'banTipo' => $tipo)))
        {
            foreach($banno as $value)
            {
                // total de productos
                $total ++;

                // cantidad de baños arrendados
                if($arriendo = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbBannoFk' => $value->getBanIdPk(), 'dnnbActivo' => 1 )) )
                {
                    $arrendados ++;
                }
            }

            $porcentaje = number_format( ($arrendados * 100)/$total, 1, '.', ',' );

            $result = array(
                'porcentaje'    => $porcentaje,
                'arrendados'    => $arrendados
                );

        }

        return $result;

    }
}