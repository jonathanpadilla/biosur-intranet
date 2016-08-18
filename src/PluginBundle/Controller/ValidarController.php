<?php

namespace PluginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ValidarController extends Controller
{
    public function rutAction(Request $request)
    {
        if($usuRut = $request->get('rut', false))
        {

            $em = $this->getDoctrine()->getManager();
            // buscar rut
            $qb = $em->createQueryBuilder();
            $q  = $qb->select(array('r'))
                    ->from('BaseBundle:Usuario', 'r')
                    ->where("r.usuRut LIKE '".$usuRut."'" )
                    ->getQuery();

            $resultQuery = $q->getResult();

            if($resultQuery)
            {
                $rut = true;
            }else{
                $rut = false;
            }

            $result = true;
        }else{
            $result = false;
            $rut    = false;
        }

    	echo json_encode(array('result' => $result, 'rut' => $rut));
        exit;
    }
}