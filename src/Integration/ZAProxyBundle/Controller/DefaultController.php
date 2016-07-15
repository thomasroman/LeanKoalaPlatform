<?php

namespace LeanKoala\Integration\ZAProxyBundle\Controller;


use Koalamon\Bundle\IncidentDashboardBundle\Entity\UserRole;
use Koalamon\Bundle\IntegrationBundle\Controller\SystemAwareIntegrationController;

class DefaultController extends SystemAwareIntegrationController
{
    const API_KEY = '98e3dcd5-3808-4a88-9abc-9ab9cbc3fddf';
    const INTEGRATION_ID = 'ZAProxySecurityScanner';

    protected function getIntegrationIdentifier()
    {
        return self::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return self::API_KEY;
    }

    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        return $this->render('LeanKoalaIntegrationZAProxyBundle:Default:index.html.twig',
        [
            'config' => $this->getConfig(),
            'systems' => $this->getSystems(),
            'integratedSystems' => $this->getIntegratedSystems(),
            'optionsTemplate' => 'LeanKoalaIntegrationZAProxyBundle:Default:options.html.twig',
            'optionsInTable' => false,
            'storePath' => $this->generateUrl('leankoala_integration_zaproxy_store', ['project' => $this->getProject()->getIdentifier()])
        ]);
    }
}
