<?php

namespace LeanKoala\Integration\SiteInfoBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;

class DefaultController extends SystemAwareIntegrationController
{
    const API_KEY = '27010d2a-5617-ad4u-9f0d-993edf547abc';
    const INTEGRATION_ID = 'SiteInfo';

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

        return $this->render('LeanKoalaIntegrationSiteInfoBundle:Default:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationSiteInfoBundle:Default:options.html.twig',
                'storePath' => $this->generateUrl('leankoala_integration_site_info_store', ['project' => $this->getProject()->getIdentifier()])
            ]);
    }
}
