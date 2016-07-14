<?php

namespace LeanKoala\Integration\ZAProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LeanKoalaIntegrationZAProxyBundle:Default:index.html.twig');
    }
}
