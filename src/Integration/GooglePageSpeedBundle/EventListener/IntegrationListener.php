<?php

namespace LeanKoala\Integration\GooglePageSpeedBundle\EventListener;

use Koalamon\Bundle\IntegrationBundle\EventListener\IntegrationInitEvent;
use Koalamon\Bundle\IntegrationBundle\Integration\Integration;
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
        $url = $this->router->generate('leankoala_integration_google_page_speed_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('Google Page Speed', '/bundles/leankoalaintegrationgooglepagespeed/images/integration.png', 'Check the google page speed score.', $url));
    }
}