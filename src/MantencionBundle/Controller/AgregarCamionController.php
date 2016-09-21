<?php

namespace MantencionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Entity\Camion;
use \stdClass;

class AgregarCamionController extends Controller
{
    public function agregarCamionAction()
    {
        // validar session y permisos
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData    = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Rutas'));
        $userData       = $this->get('service.user.data');

        // variables
        $em             = $this->getDoctrine()->getManager();
        $qb             = $em->createQueryBuilder();
        $listaCamiones  = array();
        $listaUsuario   = array();

        // cargar camiones
        $q  = $qb->select(array('c'))
            ->from('BaseBundle:Camion', 'c')
            ->where('c.camSucursalFk = '.$userData->getUserData()->sucursalActiva)
            ->getQuery();


        if($resultQuery = $q->getResult())
        {
            foreach($resultQuery as $value)
            {
                $id = $value->getCamIdPk();
                $listaCamiones[$id]['id'] = $id;
                $listaCamiones[$id]['patente'] = $value->getCamPatente();
                $listaCamiones[$id]['usuario'] = ($value->getCamUsuarioFk())?$value->getCamUsuarioFk()->getUsuIdPk():0;
            }
        }

        // cargar choferes
        $q2  = $qb->select(array('u'))
            ->from('BaseBundle:Usuario', 'u')
            ->innerJoin('BaseBundle:UsuarioNnSucursal', 'n', 'WITH', 'n.unnsSucursalFk = '.$userData->getUserData()->sucursalActiva)
            ->where('u.usuTipo = 3')
            ->getQuery();


        if($resultQuery = $q2->getResult())
        {
            foreach($resultQuery as $value)
            {
                $id = $value->getUsuIdPk();
                $listaUsuario[$id]['id']       = $id;
                $listaUsuario[$id]['nombre']   = $value->getUsuNombre();
                $listaUsuario[$id]['apellido'] = $value->getUsuApellido();
            }
        }

        return $this->render('MantencionBundle::agregarCamion.html.twig', array(
            'defaultData'   => $defaultData->getAll(),
            'userData'      => $userData->getUserData(),
            'listaCamiones' => $listaCamiones,
            'listaUsuario'  => $listaUsuario,
        ));

    }

    public function guardarAsignacionCamionAction(Request $request)
    {

        $result = false;

        if($request->getMethod() == 'POST')
        {
            $select_camion  = $request->get('select_camion', false);
            $em             = $this->getDoctrine()->getManager();

            foreach($select_camion as $key => $value)
            {
                // claves foraneas
                $fkUsuario = $em->getRepository('BaseBundle:Usuario')->findOneBy(array('usuIdPk' => $value));

                // buscar camion
                if($camion = $em->getRepository('BaseBundle:Camion')->findOneBy(array('camIdPk' => $key)))
                {
                    $camion->setCamUsuarioFk($fkUsuario);
                    $em->persist($camion);
                }

            }

            $em->flush();
            $result = true;

        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function agregarNuevoCamionAction(Request $request)
    {
        // validar session y permisos
        if(!$this->get('service.user.data')->ValidarSession('Mantenciones')){return $this->redirectToRoute('base_vista_ingreso');}

        $result = false;
        $patente    = ($request->get('patente', false))? $request->get('patente', false): false;
        $em         = $this->getDoctrine()->getManager();

        // servicios
        $userData = $this->get('service.user.data');

        if($patente)
        {
            // foraneas
            $fkSucursal = $em->getRepository('BaseBundle:Sucursal')->findOneBy(array('sucIdPk' => $userData->getUserData()->sucursalActiva ));
            
            $camion = new Camion();

            $camion->setCamPatente($patente);
            $camion->setCamActivo(1);
            $camion->setCamFecharegistro(new \DateTime(date("Y-m-d H:i:s")));
            $camion->setCamSucursalFk($fkSucursal);
            $em->persist($camion);
            $em->flush();

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }
}
