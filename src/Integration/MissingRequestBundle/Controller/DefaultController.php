<?php

namespace LeanKoala\Integration\MissingRequestBundle\Controller;

use Koalamon\Bundle\IncidentDashboardBundle\Controller\ProjectAwareController;
use Koalamon\Bundle\IncidentDashboardBundle\Entity\System;

class DefaultController extends ProjectAwareController
{
    const INTEGRATION_KEY = 'b312997e-122a-45ac-b25b-f1f2fd8effe4';

    /**
     * @param System $system
     */
    private function getCollections(System $system)
    {
        return $this->getDoctrine()
            ->getRepository('LeanKoalaIntegrationMissingRequestBundle:Collection')
            ->findBySystem($system);
    }

    public function indexAction()
    {
        $systems = $this->getDoctrine()
            ->getRepository('KoalamonIncidentDashboardBundle:System')
            ->findBy(['project' => $this->getProject(), 'parent' => null], ['name' => 'ASC']);

        $systemCollections = [];

        foreach ($systems as $system) {

            $subSystems = array();

            foreach ($system->getSubsystems() as $subsystem) {
                $subSystems[] = ['system' => $subsystem, 'collections' => $this->getCollections($subsystem)];
            }

            $systemCollections[] = ['system' => $system, 'collections' => $this->getCollections($system), 'subsystems' => $subSystems];
        }

        $collections = $this->getDoctrine()->getRepository('LeanKoalaIntegrationMissingRequestBundle:Collection')->findBy(['project' => $this->getProject()], ['name' => 'ASC']);

        return $this->render('LeanKoalaIntegrationMissingRequestBundle:Default:index.html.twig', ['collections' => $collections, 'systemCollections' => $systemCollections]);
    }
}
