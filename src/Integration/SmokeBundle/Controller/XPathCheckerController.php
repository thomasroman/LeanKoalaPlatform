<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;

class XPathCheckerController extends SystemAwareIntegrationController
{
    const INTEGRATION_ID = "xpathchecker";

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
        return $this->render('LeanKoalaIntegrationSmokeBundle:XPathChecker:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationSmokeBundle:XPathChecker:options.html.twig',
                'storePath' => $this->generateUrl('leankoala_integration_smoke_xpath_store', ['project' => $this->getProject()->getIdentifier()]),
            ]);
    }
}
