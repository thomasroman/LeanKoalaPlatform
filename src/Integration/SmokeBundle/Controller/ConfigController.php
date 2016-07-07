<?php

namespace LeanKoala\Integration\SmokeBundle\Controller;

use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingConfig;
use LeanKoala\Integration\KoalaPingBundle\Entity\KoalaPingSystem;
use Koalamon\Bundle\IntegrationBundle\Controller\SystemAwareIntegrationController;
use Koalamon\Bundle\IntegrationBundle\Entity\IntegrationConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;


class ConfigController extends SystemAwareIntegrationController
{
    // @todo should be done inside the config file
    const API_KEY = '1785d2a-5617-4da2-9f0d-993edf547522';
    const INTEGRATION_ID = 'Smoke';

    protected function getIntegrationIdentifier()
    {
        return self::INTEGRATION_ID;
    }

    protected function getApiKey()
    {
        return self::API_KEY;
    }

    public function indexAction(Request $request)
    {
        if ($request->get('integration_key') != $this->getApiKey()) {
            return new JsonResponse(['status' => "failure", 'message' => 'Integration key invalid.']);
        }

        // @todo select only smoke using projects
        $projects = $this->getDoctrine()
            ->getRepository('KoalamonIncidentDashboardBundle:Project')
            ->findAll();

        $projectUrls = [];

        foreach ($projects as $project) {
            $projectUrls[] = $this->generateUrl('leankoala_integration_smoke_config_project', ['project' => $project->getIdentifier()], true) . '?api_key=' . $project->getApiKey();
        }

        return new JsonResponse($projectUrls);
    }

    private function getActiveLittleSeoConfig()
    {
        $littleSeoSystems = $this->getActiveSystemsForProject($this->getProject(), LittleSeoController::INTEGRATION_ID, true);

        $activeSystems = array();

        $rule = ['class' => 'whm\Smoke\Rules\Seo\RobotsDisallowAllRule'];

        foreach ($littleSeoSystems as $littleSeoSystem) {

            foreach ($littleSeoSystem as $system) {
                if (array_key_exists('seoRobotsTxt', $system['options']) && $system['options']['seoRobotsTxt'] == "on") {
                    $activeSystems[] = $system['system'];
                }
            }
        }

        return ['LittleSeo_Robots' => ['systems' => $activeSystems, 'rule' => $rule]];
    }

    private function getHttpsCertConfig()
    {
        $httpsCertSystems = $this->getActiveSystemsForProject($this->getProject(), HttpsCertController::INTEGRATION_ID, true);

        $activeSystems = array();

        foreach ($httpsCertSystems as $httpsCertSystem) {

            foreach ($httpsCertSystem as $system) {
                if (array_key_exists('expireWarningTime', $system['options'])) {
                    $rule = ['class' => 'whm\Smoke\Rules\Http\HttpsCertificateExpireRule', 'parameters' => ['expireWarningTime' => (int)$system['options']['expireWarningTime']]];
                    $identifier = 'HttpCert_' . $system['system']->getId();
                    $activeSystems[$identifier] = ['systems' => [$system['system']], 'rule' => $rule];
                }
            }
        }

        return $activeSystems;
    }

    private function getJsonValidatorConfig()
    {
        $jsonSystems = $this->getActiveSystemsForProject($this->getProject(), JsonValidatorController::INTEGRATION_ID, true);

        $rule = ['class' => 'whm\Smoke\Rules\Json\ValidRule'];

        $activeSystems = [];

        foreach ($jsonSystems as $jsonSystem) {
            foreach ($jsonSystem as $system) {
                $activeSystems[] = $system['system'];
            }
        }

        return ['JsonValidator_Default' => ['systems' => $activeSystems, 'rule' => $rule]];
    }

    private function getHttpHeadConfig()
    {
        $httpHeadMainSystems = $this->getActiveSystemsForProject($this->getProject(), HttpHeadController::INTEGRATION_ID, true);

        $activeSystems = [];
        $rules[] = [];

        foreach ($httpHeadMainSystems as $httpHeadMainSystem) {

            foreach ($httpHeadMainSystem as $system) {

                $identifier = 'HttpHead_' . $system['system']->getId();

                $checkedHeaders = $system['options']['checkedHeaders'];

                if ($checkedHeaders == null) {
                    continue;
                }

                $rule = ['class' => 'whm\Smoke\Rules\Http\Header\ExistsRule', 'parameters' => ['checkedHeaders' => $checkedHeaders]];

                $activeSystems[$identifier] = ['systems' => [$system['system']], 'rule' => $rule];
            }
        }

        return $activeSystems;
    }

    /**
     * @return array
     */
    private function getXPathConfig()
    {
        $xpathSystems = $this->getActiveSystemsForProject($this->getProject(), XPathCheckerController::INTEGRATION_ID, true);

        $activeSystems = [];
        $rules[] = [];

        foreach ($xpathSystems as $xpathSystem) {

            foreach ($xpathSystem as $system) {
                $identifier = 'XPathChecker_' . $system['system']->getId();

                $xpaths = [];
                foreach ($system['options']['checkedXPaths'] as $xpathOption) {
                    $xpaths[] = $xpathOption;
                }

                if (is_null($xpaths)) {
                    $xpathRules = [];
                } else {
                    $xpathRules = array_values($xpaths);
                }
                $rule = ['class' => 'whm\Smoke\Rules\Html\XPathExistsRule', 'parameters' => ['xPaths' => $xpathRules]];

                $activeSystems[$identifier] = ['systems' => [$system['system']], 'rule' => $rule];
            }
        }

        return $activeSystems;
    }

    /**
     * @return array
     */
    private function getRegExExistsConfig()
    {
        $systems = $this->getActiveSystemsForProject($this->getProject(), RegExExistsController::INTEGRATION_ID, true);

        $activeSystems = [];
        $rules[] = [];

        foreach ($systems as $regExSystem) {

            foreach ($regExSystem as $system) {
                $identifier = 'RegExExists_' . $system['system']->getId();

                $regExs = [];

                foreach ($system['options']['regex'] as $regExOptions) {
                    $regExs[] = $regExOptions['regex'];
                }

                if (is_null($regExs)) {
                    $regExRules = [];
                } else {
                    $regExRules = array_values($regExs);
                }
                $rule = ['class' => 'whm\Smoke\Rules\Html\RegExExistsRule', 'parameters' => ['regExs' => $regExRules]];

                $activeSystems[$identifier] = ['systems' => [$system['system']], 'rule' => $rule];
            }
        }

        return $activeSystems;
    }

    private function extractSystems($activeSystems)
    {
        $allSystems = [];

        foreach ($activeSystems as $rule => $config) {
            foreach ($config['systems'] as $system) {
                $allSystems[$system->getIdentifier()][$system->getUrl()] = $system->getUrl();
            }
        }
        return $allSystems;
    }

    public function projectAction(Request $request)
    {
        $this->assertApiKey($request->get('api_key'));

        $activeSystems = $this->getActiveLittleSeoConfig();
        $activeSystems = array_merge($activeSystems, $this->getXPathConfig());
        $activeSystems = array_merge($activeSystems, $this->getJsonValidatorConfig());
        $activeSystems = array_merge($activeSystems, $this->getHttpHeadConfig());
        $activeSystems = array_merge($activeSystems, $this->getRegExExistsConfig());
        $activeSystems = array_merge($activeSystems, $this->getHttpsCertConfig());

        $rules = ['rules' => []];
        foreach ($activeSystems as $key => $activeSystem) {
            $rules['rules'][$key] = $activeSystem['rule'];
        }

        return $this->render('LeanKoalaIntegrationSmokeBundle:Config:smoke.yml.twig',
            [
                'activeSystems' => $activeSystems,
                'rules' => Yaml::dump($rules, 6, 2),
                'allSystems' => $this->extractSystems($activeSystems)
            ]);
    }
}
