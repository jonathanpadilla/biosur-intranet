<?php

namespace BodegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Endroid\QrCode\QrCode;
use \stdClass;

class ListaBannosController extends Controller
{
    public function listaBannosAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

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
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
        $first          = ($request->get('first', false))   ?$request->get('first')     :0;
        $max            = ($request->get('max', false))     ?$request->get('max')       :50;
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
                $datos = new stdClass();

                if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbBannoFk' => $value->getBanIdPk(), 'dnnbActivo' => 1 )))
                {
                    $datos->enbodega            = 0;
                    $datos->cliente_nombre      = $dnnb->getDnnbDetcontratoFk()->getDcoVentaFk()->getVenClienteFk()->getCliNombre();
                    $datos->cliente_direccion   = $dnnb->getDnnbDetcontratoFk()->getDcoDireccion();
                    $datos->cliente_comuna      = $dnnb->getDnnbDetcontratoFk()->getDcoComunaFk()->getComNombre();
                }else{
                    $datos->enbodega    = 1;
                    $datos->cliente_nombre      = '';
                    $datos->cliente_direccion   = '';
                    $datos->cliente_comuna      = '';
                }


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

                $listaBannos[] = $datos;

            }

            $result = true;
        }

        return $this->render('BodegaBundle:Layouts:item_bannos.html.twig', array(
            'listaBannos' => $listaBannos
        ));
    }

    public function excelListaBanosAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession('Bodega')){return $this->redirectToRoute('base_vista_ingreso');}

        $userData = $this->get('service.user.data');

        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();

        $where = 'b.banSucursalFk = '.$userData->getUserData()->sucursalActiva;
        $q  = $qb->select(array('b'))
                    ->from('BaseBundle:Bannos', 'b')
                    ->where($where)
                    ->orderBy('b.banIdPk', 'ASC')
                    ->getQuery();

        if($resultQuery = $q->getResult())
        {
            // exportar a excel
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator("Intranet Biosur")
                ->setLastModifiedBy("Intranet Biosur")
                ->setTitle("Lista productos")
                ->setSubject("Lista productos")
                ->setDescription("Lista productos")
                ->setKeywords("lista productos banos casetas duchas lavamanos externos");

            $phpExcelObject->setActiveSheetIndex(0);
            $phpExcelObject->getActiveSheet()->setTitle('Lista productos');

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B2', 'Código')
                ->setCellValue('C2', 'Tipo')
                ->setCellValue('D2', 'Categoria')
                ->setCellValue('E2', 'Propiedad')
                ->setCellValue('F2', 'Asignado')
                ->setCellValue('G2', 'Estado');

            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('B')
                ->setWidth(12);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('C')
                ->setWidth(20);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('D')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('E')
                ->setWidth(20);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('F')
                ->setWidth(10);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('G')
                ->setWidth(20);

            $row = 3;

            // resultados
            $enbodega = 1;
            foreach($resultQuery as $value)
            {

                if($dnnb = $em->getRepository('BaseBundle:DetcontratoNnBanno')->findOneBy(array('dnnbBannoFk' => $value->getBanIdPk(), 'dnnbActivo' => 1 )))
                {
                    $enbodega            = 0;
                    $cliente_nombre      = $dnnb->getDnnbDetcontratoFk()->getDcoVentaFk()->getVenClienteFk()->getCliNombre();
                    $cliente_direccion   = $dnnb->getDnnbDetcontratoFk()->getDcoDireccion();
                    $cliente_comuna      = $dnnb->getDnnbDetcontratoFk()->getDcoComunaFk()->getComNombre();
                }else{
                    $enbodega    = 1;
                    $cliente_nombre      = null;
                    $cliente_direccion   = null;
                    $cliente_comuna      = null;
                }


                $id = str_pad($value->getBanIdPk(), 7, '0', STR_PAD_LEFT);

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

                $estado = (!$enbodega)? $cliente_nombre.', '.$cliente_direccion.', '.$cliente_comuna: 'En Bodega' ;

                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B'.$row, $letra.$id)
                    ->setCellValue('C'.$row, $tipo.' ('.$letra.')')
                    ->setCellValue('D'.$row, (($value->getBanTipo2())? $value->getBanTipo2()->getBtiNombre(): '') )
                    ->setCellValue('E'.$row, (($value->getBanClienteFk())? $value->getBanClienteFk()->getCliNombre():'Biosur') )
                    ->setCellValue('F'.$row, (($value->getBanAsignado() != 0)? 'SI': 'NO') )
                    ->setCellValue('G'.$row, $estado );

                $row++;

            }

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'Lista productos.xls'
            );

            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);


        }

        return $response;

    }
}
