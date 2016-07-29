<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\System;
use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;
use Koalamon\IntegrationBundle\Util\SystemFilter;


class HttpsCertController extends SystemAwareIntegrationController
{
    const INTEGRATION_ID = 'HttpsCert';

    protected function getIntegrationIdentifier()
    {
        return self::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return ConfigController::API_KEY;
    }

    /**
     * @param System[] $systems
     * @return System[]
     */
    protected function filterSystems(array $systems)
    {
        return SystemFilter::filterHttps($systems);
    }

    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);
        return $this->render('LeanKoalaIntegrationSmokeBundle:HttpsCert:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationSmokeBundle:HttpsCert:options.html.twig',
                'optionsInTable' => false,
                'showSubsystems' => true,
                'storePath' => $this->generateUrl('leankoala_integration_smoke_https_cert_store', ['project' => $this->getProject()->getIdentifier()]),
            ]);
    }
}
