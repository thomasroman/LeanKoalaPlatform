<?php

namespace LeanKoala\Integration\MissingRequestBundle\Controller;

use Koalamon\IncidentDashboardBundle\Controller\ProjectAwareController;
use Koalamon\IncidentDashboardBundle\Entity\System;
use Koalamon\IncidentDashboardBundle\Entity\UserRole;
use LeanKoala\Integration\MissingRequestBundle\Entity\Collection;
use LeanKoala\Integration\MissingRequestBundle\Entity\Request;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SystemController extends ProjectAwareController
{
    public function editAction(System $system)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $collections = $this->getDoctrine()
            ->getRepository('LeanKoalaIntegrationMissingRequestBundle:Collection')
            ->findBySystem($system);

        $allCollections = $this->getDoctrine()
            ->getRepository('LeanKoalaIntegrationMissingRequestBundle:Collection')
            ->findBy(['project' => $this->getProject()], ['name' => 'ASC']);

        return $this->render('LeanKoalaIntegrationMissingRequestBundle:System:edit.html.twig', ['system' => $system, 'collections' => $collections, 'allCollections' => $allCollections]);
    }

    public function storeAction(\Symfony\Component\HttpFoundation\Request $request, System $system)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $collections = $this->getDoctrine()
            ->getRepository('LeanKoalaIntegrationMissingRequestBundle:Collection')
            ->findBySystem($system);

        $em = $this->getDoctrine()->getManager();

        foreach ($collections as $collection) {
            $collection->removeSystem($system);
            $em->persist($collection);
        }

        $em->flush();

        $collections = array_keys((array)$request->get('collections'));

        foreach ($collections as $collection) {
            $collectionObj = $this->getDoctrine()
                ->getRepository('LeanKoalaIntegrationMissingRequestBundle:Collection')
                ->find($collection);
            $collectionObj->addSystem($system);
            $em->persist($collectionObj);
        }

        $em->flush();

        return $this->redirectToRoute('leankoala_integration_missing_request_homepage');
    }
}
