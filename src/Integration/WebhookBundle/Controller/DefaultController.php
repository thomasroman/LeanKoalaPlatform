<?php

namespace LeanKoala\Integration\WebhookBundle\Controller;

use Koalamon\Bundle\IncidentDashboardBundle\Controller\ProjectAwareController;

class DefaultController extends ProjectAwareController
{
    public function newRelicAction($hookName)
    {
        return $this->render('LeanKoalaIntegrationWebhookBundle:Default:' . $hookName . '.html.twig');
    }
}
