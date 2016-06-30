<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use Koalamon\Bundle\IncidentDashboardBundle\Entity\UserRole;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\Bundle\IntegrationBundle\Controller\SystemAwareIntegrationController;


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
