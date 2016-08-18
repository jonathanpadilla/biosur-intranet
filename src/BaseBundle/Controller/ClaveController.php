<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClaveController extends Controller
{
    public function solicitarCambioClaveAction()
    {
    	// // servicios
    	// $defaultData = $this->get('service.default.data');
    	// $defaultData->setHtmlHeader(array('title' => 'Inicio'));

        return $this->render('BaseBundle::solicitarclave.html.twig');
    }

    public function enviarSolicitudAction(Request $request)
    {

    	if( $rut = $request->get('rut', false))
        {
        	// variables
        	$em 	= $this->getDoctrine()->getManager();
        	$qb 	= $em->createQueryBuilder();

        	$q  = $qb->select(array('u'))
	            ->from('BaseBundle:Usuario', 'u')
	            ->where($qb->expr()->andX(
                            $qb->expr()->like('u.usuRut', $qb->expr()->literal('%'.$rut.'%')),
                            $qb->expr()->neq('u.usuVinculado', 0)
                        )
	            	)
	            ->getQuery();
	        $resultQuery = $q->getOneOrNullResult();

        	if($resultQuery)
	        {
	        	
	        	if($correo = $em->getRepository('BaseBundle:Contacto')->findOneBy(array('conUsuarioFk' => $resultQuery->getUsuIdPk(), 'conTipoFk' => 3)))
	        	{
	        		$correo->getConDetalle();

	        		// enviar mail

	        		$message = \Swift_Message::newInstance()
				        ->setSubject('Hello Email')
				        ->setFrom('jonathanpadilla0109@gmail.com')
				        ->setTo('jonathanpadilla09@outlook.com')
				        ->setBody('Mensaje equisdee','text/plain'
				        )
				        // ->setBody(
				        //     $this->renderView(
				        //         // app/Resources/views/Emails/registration.html.twig
				        //         'Emails/registration.html.twig',
				        //         array('name' => $name)
				        //     ),
				        //     'text/html'
				        // )
				    ;
				    $this->get('mailer')->send($message);
				    
	        		// mostrar mail censurado
	        		$nombreMail 		= strstr($correo->getConDetalle(), '@', true);
	        		$nombreServidor 	= strstr($correo->getConDetalle(), '@');
	        		$largoMail			= strlen($nombreMail);
	        		$censura 			= '';

	        		for($i = 0; $i < $largoMail - 4; $i++ )
	        		{
	        			$censura .= '*';
	        		}

	        		$restar = $largoMail * -1;

	        		$mostrar = substr($nombreMail, 0, $restar + 4).$censura.$nombreServidor;

	        		$result = true;
	        		$msg 	= 'Se envió la información al correo '.$mostrar.', válido por 24 hrs.';
	        	}else{
	        		$result = false;
	        		$msg 	= 'No existe un correo registrado para este usuario.';
	        	}

	        }else{
	        	$result = false;
        		$msg 	= 'El rut no está registrado o fue deshabilitado del sistema';
	        }
        }else{
        	$result = false;
        	$msg 	= 'error interno';
        }

    	echo json_encode(array('result' => $result, 'msg' => $msg));
    	exit;
    }
}