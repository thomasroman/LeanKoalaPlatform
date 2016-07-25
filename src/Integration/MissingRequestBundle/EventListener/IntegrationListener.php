<?php

namespace LeanKoala\Integration\MissingRequestBundle\EventListener;

use Koalamon\IntegrationBundle\EventListener\IntegrationInitEvent;
use Koalamon\IntegrationBundle\Integration\Integration;
use Symfony\Component\DependencyInjection\Container;

class IntegrationListener
{
    private $router;

    public function __construct(Container $container)
    {
        $this->router = $container->get('router');
    }

    public function onInit(IntegrationInitEvent $event)
    {
        $integrationContainer = $event->getIntegrationContainer();
        $url = $this->router->generate('leankoala_integration_missing_request_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('MissingRequest (lite)', '/images/integrations/missing_logo.png', 'Tool for checking missing http requests.', $url));
    }
}