<?php

namespace LeanKoala\Integration\SmokeBasicBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\System;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use LeanKoala\Integration\SmokeBundle\Controller\LittleSeoController;
use Koalamon\IntegrationBundle\Controller\SystemAwareIntegrationController;
use Koalamon\IntegrationBundle\Entity\IntegrationConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ConfigController extends SystemAwareIntegrationController
{
    protected function getIntegrationIdentifier()
    {
        return DefaultController::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return DefaultController::API_KEY;
    }

    public function indexAction(Request $request)
    {
        if ($request->get('integration_key') != $this->getApiKey()) {
            return new JsonResponse(['status' => "failure", 'message' => 'Integration key invalid.']);
        }

        $configs = $this->getDoctrine()
            ->getRepository('KoalamonIntegrationBundle:IntegrationConfig')
            ->findBy(['integration' => $this->getIntegrationIdentifier()]);

        $projects = [];

        foreach ($configs as $config) {
            if ($this->getActiveSystemsForProject($config->getProject(), null, true)) {
                $projects[] = $config->getProject();
            }
        }

        $projectUrls = [];

        foreach ($projects as $project) {
            $activeSystems = $this->getActiveSystemsForProject($project, null, true);
            foreach ($activeSystems as $activeSystem) {
                $projectUrls[] = $this->generateUrl('leankoala_integration_smoke_basic_config_project', ['project' => $project->getIdentifier(), 'system' => $activeSystem[0]['system']->getId()], true) . '?api_key=' . $project->getApiKey();
            }

        }

        return new JsonResponse($projectUrls);
    }

    public function projectAction(Request $request, System $system)
    {
        $this->assertApiKey($request->get('api_key'));

        $smokeBasicActiveSystems = $this->getActiveSystemsForProject($this->getProject(), null, true);

        $filters = array();
        foreach ($smokeBasicActiveSystems as $key => $activeSystem) {

            if (array_key_exists('system', $activeSystem[0]) && $activeSystem[0]['system'] == $system) {
                if (array_key_exists('excludedRules', $activeSystem[0]['options']) && is_array($activeSystem[0]['options']['excludedRules'])) {
                    foreach ($activeSystem[0]['options']['excludedRules'] as $filter) {

                        $rule = $filter['rule'];
                        $url = $filter['url'];
                        if (!array_key_exists($rule, $filters)) {
                            $filters[$rule] = array();
                        }
                        $filters[$rule][] = $url;
                    }
                }
            }
        }

        return $this->render('LeanKoalaIntegrationSmokeBasicBundle:Config:smoke.yml.twig',
            [
                'smokeBasicActiveSystems' => $smokeBasicActiveSystems,
                'filters' => $filters,
                'system' => $system
            ]);
    }
}
