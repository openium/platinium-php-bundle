<?php

namespace Openium\PlatiniumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PlatiniumBundle:Default:index.html.twig');
    }
}
