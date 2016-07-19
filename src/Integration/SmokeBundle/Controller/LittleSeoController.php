<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;


class LittleSeoController extends SystemAwareIntegrationController
{
    const INTEGRATION_ID = 'LittleSeo';

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
        return $this->render('LeanKoalaIntegrationSmokeBundle:LittleSeo:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationSmokeBundle:LittleSeo:options.html.twig',
                'optionsInTable' => true,
                'showSubsystems' => false,
                'storePath' => $this->generateUrl('leankoala_integration_smoke_seo_store', ['project' => $this->getProject()->getIdentifier()]),
            ]);
    }
}
