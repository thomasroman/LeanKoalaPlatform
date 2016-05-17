<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use Koalamon\Bundle\IncidentDashboardBundle\Entity\UserRole;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\Bundle\IntegrationBundle\Controller\SystemAwareIntegrationController;

class RegExExistsController extends SystemAwareIntegrationController
{
    const INTEGRATION_ID = "regexexists";

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
        return $this->render('LeanKoalaIntegrationSmokeBundle:RegExExists:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationSmokeBundle:RegExExists:options.html.twig',
                'storePath' => $this->generateUrl('leankoala_integration_smoke_reg_ex_exists_store', ['project' => $this->getProject()->getIdentifier()]),
            ]);
    }
}
