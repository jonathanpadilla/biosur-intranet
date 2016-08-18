<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class EstadisticaBannoController extends Controller
{
    public function graficoMenuAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $userData = $this->get('service.user.data');

        // variables
        $result     = false;
        $em         = $this->getDoctrine()->getManager();
        $tipo       = ($request->get('tipo', false))? $request->get('tipo'): null;
        $total      = 0;
        $arrendados = 0;
        $porcentaje = 0;

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

            $result = true;
        }



        echo json_encode(array('result' => $result, 'total' => $total, 'arrendados' => $arrendados, 'porcentaje' => $porcentaje ));
        exit;
    }
}
