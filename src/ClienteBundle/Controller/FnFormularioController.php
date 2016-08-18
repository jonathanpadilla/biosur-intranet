<?php

namespace ClienteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class FnFormularioController extends Controller
{
    public function validarRutClienteAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}

    	if($rut = $request->get('rut', false))
        {
        	$em = $this->getDoctrine()->getManager();
            // buscar rut
            $qb = $em->createQueryBuilder();
            $q  = $qb->select(array('c'))
                    ->from('BaseBundle:Cliente', 'c')
                    ->where("c.cliRut LIKE '".$rut."'" )
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
        	$rut 	= false;
        	$result = false;
        }

        echo json_encode(array('result' => $result, 'rut' => $rut));
        exit;
    }

    public function obtenerClientePorIdAction(Request $request)
    {
        // validar session
        if(!$this->get('service.user.data')->ValidarSession()){return $this->redirectToRoute('base_vista_ingreso');}
        
        if($id = $request->get('id', false))
        {
            // variables
            $em = $this->getDoctrine()->getManager();

            $cliente = $em->getRepository('BaseBundle:Cliente')->findOneBy(array('cliIdPk' => $id ));

            $datosCliente = array(
                'nombre'        => $cliente->getCliNombre(),
                'rut'           => $cliente->getCliRut(),
                'giro'          => $cliente->getCliGiro(),
                'direccion'     => $cliente->getCliDireccion(),
                'comentario'    => $cliente->getCliComentario(),
                'comuna'        => $cliente->getCliComunaFk()->getComIdPk()
                );

            $contacto = $em->getRepository('BaseBundle:Contacto')->findBy(array('conClienteFk' => $id ));

            $datosContacto = '';
            if($contacto)
            {
                $countContacto = 1;
                foreach($contacto as $value)
                {
                    $datosContacto.= '<tr id="'.$countContacto.'">';
                    $datosContacto.= '<td><select name="datocontacto['.$countContacto.'][1]" id="datocontacto['.$countContacto.'][1]" class="form-control"><option value="default">Seleccionar</option><option value="1" '.(($value->getConTipoFk()->getCtiIdPk() == 1)?'selected':'').'>Telefono</option><option value="2" '.(($value->getConTipoFk()->getCtiIdPk() == 2)?'selected':'').'>Celular</option><option value="3" '.(($value->getConTipoFk()->getCtiIdPk() == 3)?'selected':'').'>Correo</option></select></td>';
                    $datosContacto.= '<td><input type="text" name="datocontacto['.$countContacto.'][2]" class="form-control" value="'.$value->getConDetalle().'"></td>';
                    $datosContacto.= '<td><input type="text" name="datocontacto['.$countContacto.'][3]" class="form-control" value="'.$value->getConNombrepersona().'"></td>';
                    $datosContacto.= '<td class="text-right"><div class="btn-group"><button type="button" data-id="'.$countContacto.'" class="btn btn-danger button_eliminarfilacontacto"><i class="fa fa-trash"></i></button></div></td></tr>';

                    $countContacto+= 1;
                }
            }

            $result = true;
        }else{
            $result = false;
            $datosCliente = array();
        }
        
        echo json_encode(array('result' => $result, 'datosCliente' => $datosCliente, 'datosContacto' => $datosContacto));
        exit;
    }
}