<?php

namespace LeanKoala\Integration\SmokeBundle\EventListener;

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

        $url = $this->router->generate('leankoala_integration_smoke_seo_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('LittleSEO', '/images/integrations/littleseo.png', 'Checking some search engine rules', $url));

        $url = $this->router->generate('leankoala_integration_smoke_xpath_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('XPath Checker', '/images/integrations/xpath.png', 'Checking if given XPaths do exist.', $url));

        $url = $this->router->generate('leankoala_integration_smoke_json_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('Json Validator', '/images/integrations/json.png', 'Checking if given Systems return valid json', $url));

        $url = $this->router->generate('leankoala_integration_smoke_http_head_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('Http Header Check', '/bundles/leankoalaintegrationsmoke/images/head.png', 'Checking if a given http header exists', $url));

        $url = $this->router->generate('leankoala_integration_smoke_reg_ex_exists_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('RegEx Exists', '/bundles/leankoalaintegrationsmoke/images/regex.png', 'Checks if a given regular exprrssion exists', $url));

        $url = $this->router->generate('leankoala_integration_smoke_https_cert_homepage', ['project' => $event->getProject()->getIdentifier()]);
        $integrationContainer->addIntegration(new Integration('Https Certificate Checker', '', 'Checks if the https certificate is valid', $url));
    }
}
