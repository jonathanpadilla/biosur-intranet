<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\QrCode;
use \stdClass;

class ListaBannosController extends Controller
{
    public function listaBannosAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

    	// servicios
    	$defaultData = $this->get('service.default.data');
    	$defaultData->setHtmlHeader(array('title' => 'Lista de baños'));
        $userData = $this->get('service.user.data');

        return $this->render('BodegaBundle::listaBannos.html.twig', array(
        	'defaultData' => $defaultData->getAll(),
            'userData'    => $userData->getUserData(),
            'menuActivo'  => 'listabannos'
        ));
    }

    public function cargarBannosAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $first          = ($request->get('first', false))   ?$request->get('first')     :0;
        $max            = ($request->get('max', false))     ?$request->get('max')       :0;
        // $buscar         = ($request->get('buscar', false))  ?$request->get('buscar')    :null;
        $result         = false;
        $listaBannos    = array();
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();

        $userData = $this->get('service.user.data');

        $where = 'b.banSucursalFk = '.$userData->getUserData()->sucursalActiva;
        // $where .= (is_numeric($buscar))? ' AND b.banIdPk = '.$buscar: '';

        $q  = $qb->select(array('b'))
                    ->from('BaseBundle:Bannos', 'b')
                    ->where($where)
                    ->orderBy('b.banIdPk', 'DESC')
                    ->setFirstResult($first)
                    ->setMaxResults($max)
                    ->getQuery();

        if($resultQuery = $q->getResult())
        {
            $enbodega = 1;
            foreach($resultQuery as $value)
            {
                if(!$em->getRepository('BaseBundle:DetcontratoNnBanno')->findBy(array('dnnbBannoFk' => $value->getBanIdPk(), 'dnnbActivo' => 1 )))
                {
                    $enbodega = 1;
                }else{
                    $enbodega = 0;
                }

                $datos = new stdClass();

                $datos->id = str_pad($value->getBanIdPk(), 7, '0', STR_PAD_LEFT);

                $letra  = '';
                $tipo   = '';
                // var_dump($value->getBanTipo2());exit;
                switch($value->getBanTipo())
                {
                    case 1:
                        $letra  = 'B';
                        $tipo   = 'Baño';
                        break;
                    case 2: 
                        $letra  = 'C';
                        $tipo   = 'Caseta';
                        break;
                    case 3: 
                        $letra  = 'D';
                        $tipo   = 'Ducha';
                        break;
                    case 4: 
                        $letra  = 'E';
                        $tipo   = 'Baño externo';
                        break;
                    case 5: 
                        $letra  = 'L';
                        $tipo   = 'Lavamanos';
                        break;
                }

                $datos->id          = $letra.$datos->id;
                $datos->tipo        = $tipo;
                $datos->letra       = $letra;
                $datos->enbodega    = $enbodega;

                $listaBannos[] = $datos;

            }

            $result = true;
        }

        return $this->render('BodegaBundle:Layouts:item_bannos.html.twig', array(
            'listaBannos' => $listaBannos
        ));
    }
}
