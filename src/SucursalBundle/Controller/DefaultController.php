<?php

namespace SucursalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SucursalBundle:Default:index.html.twig');
    }
}
