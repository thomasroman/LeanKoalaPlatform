<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use Koalamon\IntegrationBundle\Util\SystemFilter;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;

class JsonValidatorController extends SystemAwareIntegrationController
{
    const INTEGRATION_ID = "jsonvalidator";

    protected function getIntegrationIdentifier()
    {
        return self::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return ConfigController::API_KEY;
    }

    protected function filterSystems(array $systems)
    {
        return SystemFilter::filterJson($systems);
    }


    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        return $this->render('LeanKoalaIntegrationSmokeBundle:JsonValidator:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsInTable' => true,
                'storePath' => $this->generateUrl('leankoala_integration_smoke_json_store', ['project' => $this->getProject()->getIdentifier()]),
            ]);
    }
}
