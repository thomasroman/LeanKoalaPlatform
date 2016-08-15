<?php

namespace LeanKoala\Integration\KoalaPingBundle\Controller;

use Koalamon\IncidentDashboardBundle\Controller\ProjectAwareController;
use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends SystemAwareIntegrationController
{
    // @todo should be done inside the config file
    const API_KEY = '27010d2a-5617-4da2-9f0d-993edf547522';
    const INTEGRATION_ID = 'KoalaPing';

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

        $statusTexts = Response::$statusTexts;
        $statusTexts[0] = 'Connection Error';

        return $this->render('@LeanKoalaIntegrationKoalaPing/Default/index.html.twig',
            [
                'config' => $this->getConfig(),
                'wp_tag' => 'koalaping',
                'systems' => $this->getSystems(),
                'statusCodes' => $statusTexts,
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationKoalaPingBundle:Default:options.html.twig',
                'optionsInTable' => false,
                'storePath' => $this->generateUrl('leankoala_integration_koala_ping_store', ['project' => $this->getProject()->getIdentifier()])
            ]);
    }
}
