<?php

namespace LeanKoala\HelpBundle\Controller;

use Koalamon\IncidentDashboardBundle\Entity\Project;
use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use KoalamonCom\Plattform\InformationBundle\Entity\Information;

class FirstStepsController extends \KoalamonCom\Plattform\HelpBundle\Controller\FirstStepsController
{
    const SUM_STEPS = 4;

    protected function hasIntegrationConfigs(Project $project)
    {
        $configs = $this
            ->getDoctrine()
            ->getRepository('KoalamonIntegrationBundle:IntegrationConfig')
            ->findOneBy(['project' => $project]);

        return count($configs) > 0;
    }

    protected function hasComponents(Project $project)
    {
        $configs = $this
            ->getDoctrine()
            ->getRepository('KoalamonIncidentDashboardBundle:System')
            ->findComponentsByProject($project);

        return count($configs) > 0;
    }

    public function listAction(Project $project)
    {
        if ($project->isSetupComplete()) {
            return $this->render('@LeanKoalaHelp/FirstSteps/empty.html.twig');
        } else {
            $currentSteps = 0;

            $hasEvents = $this->hasEventIdentifiers($project);
            if ($hasEvents) {
                $currentSteps++;
            }

            $hasComponents = $this->hasComponents($project);
            if ($hasComponents) {
                $currentSteps++;
            }

            $hasNotificationAlerts = $this->hasNotificationConfiguration($project);
            if ($hasNotificationAlerts) {
                $currentSteps++;
            }

            $hasNotificationSenders = $this->hasSenderConfiguration($project);
            if ($hasNotificationSenders) {
                $currentSteps++;
            }

            $hasIntegrationConfigs = $this->hasIntegrationConfigs($project);
            if ($hasIntegrationConfigs) {
                $currentSteps++;
            }

            if ($currentSteps == self::SUM_STEPS) {
                $project->setSetupComplete(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);

                $information = new Information();
                $information->setProject($project);
                $information->setEditAccess(UserRole::ROLE_ADMIN);
                $information->setEndDate((new \DateTime())->modify('+24 hours'));
                $information->setReadAccess(UserRole::ROLE_ADMIN);
                $information->setMessage('Setup complete. Have fun using LeanKoala.');

                $em->persist($information);
                $em->flush();
            }

            return $this->render('@LeanKoalaHelp/FirstSteps/list.html.twig',
                [
                    'sumSteps' => self::SUM_STEPS,
                    'currentSteps' => $currentSteps,
                    'hasComponents' => $hasComponents,
                    'hasNotificationSenders' => $hasNotificationSenders,
                    'hasNotificationAlerts' => $hasNotificationAlerts,
                    'hasIntegrationConfigs' => $hasIntegrationConfigs,
                    'project' => $project
                ]);

        }
    }
}
