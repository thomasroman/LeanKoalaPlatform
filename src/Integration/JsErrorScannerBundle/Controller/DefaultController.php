<?php

namespace LeanKoala\Integration\JsErrorScannerBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;
use Koalamon\IntegrationBundle\Util\SystemFilter;

class DefaultController extends SystemAwareIntegrationController
{
    const API_KEY = '27010d2a-5617-4da2-9f0d-993edf547abc';
    const INTEGRATION_ID = 'JsErrorScanner';

    protected function getIntegrationIdentifier()
    {
        return self::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return self::API_KEY;
    }

    protected function filterSystems(array $systems)
    {
        return SystemFilter::filterHtml($systems);
    }

    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        return $this->render('LeanKoalaIntegrationJsErrorScannerBundle:Default:index.html.twig',
            [
                'config' => $this->getConfig(),
                'systems' => $this->getSystems(),
                'integratedSystems' => $this->getIntegratedSystems(),
                'optionsTemplate' => 'LeanKoalaIntegrationJsErrorScannerBundle:Default:options.html.twig',
                'optionsInTable' => false,
                'storePath' => $this->generateUrl('leankoala_integration_js_error_scanner_store', ['project' => $this->getProject()->getIdentifier()])
            ]);
    }
}
