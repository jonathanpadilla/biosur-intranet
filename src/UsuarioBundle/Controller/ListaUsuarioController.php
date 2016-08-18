<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class ListaUsuarioController extends Controller
{

/**
VISTAS
*/
    public function listaUsuarioAction()
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // servicios
        $defaultData = $this->get('service.default.data');
        $defaultData->setHtmlHeader(array('title' => 'Inicio'));
        $userData = $this->get('service.user.data');
        
        return $this->render('UsuarioBundle::listaUsuario.html.twig', array(
            'defaultData' => $defaultData->getAll(),
            'userData'      => $userData->getUserData()
            ));
    }

/**
FUNCIONES AJAX
*/
	public function cargarListaAction(Request $request)
	{
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        // variables
		$em     = $this->getDoctrine()->getManager();
        $qb     = $em->createQueryBuilder();
        $limite = 50;
        $pagina = ($request->get('pagina', 0))?$request->get('pagina')*$limite:0;

        $where = 'u.usuVinculado <> 0';
        // filtros
        if($request->getMethod() == 'POST')
        {
            $id         = ($request->get('id', false))?         $request->get('id'):        null;
            $nombre     = ($request->get('nombre', false))?     $request->get('nombre'):    null;
            $direccion  = ($request->get('direccion', false))?  $request->get('direccion'): null;
            $perfil     = ($request->get('perfil', false))?     $request->get('perfil'):    null;
            $sucursal   = ($request->get('sucursal', false))?   $request->get('sucursal'):  null;

            $where.= ($id)?                     ' AND u.usuIdPk = '.$id:'';
            $where.= ($nombre)?                 " AND u.usuNombre LIKE '%".$nombre."%' OR u.usuApellido LIKE '%".$nombre."%'":'';
            $where.= ($direccion)?              " AND u.usuDireccion LIKE '%".$direccion."%'":'';
            $where.= (is_numeric($perfil))?     ' AND u.usuTipo = '.$perfil:'';
            // $where.= (is_numeric($sucursal))?   ' AND p.unnsSucursalFk = '.$sucursal:'';

        }

        // echo $where;exit;

	    // cargar lista
	    $q  = $qb->select(array('u'))
                    ->from('BaseBundle:Usuario', 'u')
                    ->where($where)
                    ->setFirstResult( $pagina )
                    ->setMaxResults( $limite )
                    ->getQuery();
        $resultQuery = $q->getResult();

        // $q  = $qb->select(array('u'))
        //             ->from('BaseBundle:Usuario', 'u')
        //             ->innerJoin('BaseBundle:UsuarioNnSucursal', 'p', 'p.unnsUsuarioFk = u.usuIdPk')
        //             ->where('u.usuVinculado <> 0 AND p.unnsSucursalFk = 3')
        //             ->getQuery();
        // $resultQuery = $q->getResult();

        if($resultQuery)
        {
        	$cargarLista = '';
        	foreach($resultQuery as $value)
        	{
                // foraneas
                $nnContacto = $em->getRepository('BaseBundle:Contacto')
                                ->findOneBy(array('conUsuarioFk' => $value->getUsuIdPk()));

                switch($value->getUsuTipo())
                {
                    case 1:
                        $perfil = '<span class="label label-dark">Administrador</span>';
                        break;
                    case 2:
                        $perfil = '<span class="label label-system">Ejecutivo</span>';
                        break;
                    case 3:
                        $perfil = '<span class="label label-primary">Chofer</span>';
                        break;
                }

                if($nnContacto)
                {
                    if($nnContacto->getConTipoFk()->getCtiIdPk() == 1)
                    {
                        $contacto = 'Tel: '.$nnContacto->getConDetalle();
                    }elseif($nnContacto->getConTipoFk()->getCtiIdPk() == 2){
                        $contacto = 'Cel: '.$nnContacto->getConDetalle();
                    }elseif($nnContacto->getConTipoFk()->getCtiIdPk() == 3){
                        $contacto = 'Correo: '.$nnContacto->getConDetalle();
                    }
                }

                // sucursales de usuarios
                $qb = $em->createQueryBuilder();
                $q  = $qb->select(array('s'))
                    ->from('BaseBundle:UsuarioNnSucursal', 's')
                    ->where($qb->expr()->andX(
                            $qb->expr()->eq('s.unnsUsuarioFk', $value->getUsuIdPk()),
                            $qb->expr()->eq('s.unnsHabilitado', 1)
                            ))
                    ->getQuery();

                $resultQuery = $q->getResult();

                if($resultQuery)
                {
                    $usuarioSucursal = '';
                    $coma = 0;
                    foreach($resultQuery as $value2)
                    {
                        $sucursalId           = $value2->getUnnsSucursalFk()->getSucIdPk();
                        $objkSucursal    = $em->getRepository('BaseBundle:Sucursal')
                                                ->findOneBy(array('sucIdPk' => $sucursalId));
                        $usuarioSucursal.= $objkSucursal->getSucNombre().', ';

                    }
                }

                // crear lista
        		$cargarLista.= '<tr>';
        		$cargarLista.= '<td>'.str_pad($value->getUsuIdPk(), 6, "0", STR_PAD_LEFT).'</td>';
        		$cargarLista.= '<td>'.$value->getUsuNombre().' '.$value->getUsuApellido().'</td>';
        		$cargarLista.= '<td><span data-toggle="tooltip" title="Poner tooltip con nombre de contacto">'.$contacto.'</span></td>';
        		$cargarLista.= '<td>'.$perfil.'</td>';
        		$cargarLista.= '<td>'.$usuarioSucursal.'</td>';
        		$cargarLista.= '<td>'.$value->getUsuDireccion().'</td>';
        		$cargarLista.= '<td class="text-right"><div class="btn-group">';
        		$cargarLista.= '<a href="'.$this->get('router')->generate('usuario_vista_ver', array('id' => $value->getUsuIdPk() )).'" class="btn btn-sm btn-default"><i class="fa fa-user"></i></a>';
        		$cargarLista.= '<button type="button" data-id="'.$value->getUsuIdPk().'" class="btn btn-sm btn-danger button_eliminarUsuario"><i class="fa fa-trash"></i></button>';
        		$cargarLista.= '</div></td></tr>';
        	}

            // paginacion
            $q2  = $qb->select(array('u'))
                    ->from('BaseBundle:Usuario', 'u')
                    ->where($where)
                    ->getQuery();
            $resultQuery2 = $q2->getResult();

            $registros = count($resultQuery2);
            $total_paginas = ceil($registros / $limite);

            $paginador = '';
            if($total_paginas > 1)
            {
                for($i = 0; $i<$total_paginas; $i++)
                {
                    $pag = $i + 1;
                    $paginador.= '<button type="button" class="btn btn-sm btn-default btn_pagina" data-pag="'.$pag.'">'.$pag.'</button>';
                }
            }

            $result = true;
        }else{
            $result         = false;
            $cargarLista    = false;
        }

		echo json_encode(array('result' => $result, 'lista' => $cargarLista, 'paginador' => $paginador));
		exit;
	}

    public function eliminarUsuarioAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

        if($request->getMethod() == 'POST')
        {
            // variables
            $em = $this->getDoctrine()->getManager();
            $id = ($request->get('id', false))?$request->get('id'):null;

            $usuario = $em->getRepository('BaseBundle:Usuario')
                            ->findOneBy(array('usuIdPk' => $id));

            if($usuario)
            {
                $usuario->setUsuVinculado(0);
                $em->flush();

                $result = true;
            }else{
                $result = false;
            }

            echo json_encode(array('result' => $result));
        }else{
            return $this->redirectToRoute('usuario_vista_listar');
        }

        exit;
    }
}
