<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests;

use Ostrolucky\TraceRoute\Model\RouteVisit;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ControllerTest extends AbstractTestCase
{
    public function testRouteVisitsAreRecorded(): void
    {
        $container = $this->bootKernel()->getContainer();
        $em = $container->get('doctrine.orm.default_entity_manager');

        /** @var KernelBrowser $client */
        $client = $container->get('test.client');
        $client->disableReboot();

        $this->assertCount(0, $em->getRepository(RouteVisit::class)->findAll());

        $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $routeVisits = $em->getRepository(RouteVisit::class)->findAll();
        $this->assertCount(1, $routeVisits);
        $this->assertSame('ostrolucky_traceroute_tests_fixtures_test_index', $routeVisits[0]->getRoute());
        $this->assertSame('Ostrolucky\TraceRoute\Tests\Fixtures\Controller\TestController::indexAction', $routeVisits[0]->getController());
        $this->assertSame(1, $routeVisits[0]->getVisitCount());
        $date = $routeVisits[0]->getLastUpdatedAt();
        $this->assertInstanceOf(\DateTimeInterface::class, $date);

        $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $routeVisits = $em->getRepository(RouteVisit::class)->findAll();
        $this->assertCount(1, $routeVisits);
        $this->assertSame('ostrolucky_traceroute_tests_fixtures_test_index', $routeVisits[0]->getRoute());
        $this->assertSame('Ostrolucky\TraceRoute\Tests\Fixtures\Controller\TestController::indexAction', $routeVisits[0]->getController());
        $this->assertSame(2, $routeVisits[0]->getVisitCount());
        $this->assertGreaterThan($date, $routeVisits[0]->getLastUpdatedAt());

        $client->request('GET', '/foo');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $routeVisits = $em->getRepository(RouteVisit::class)->findAll();
        $this->assertCount(2, $routeVisits);
        $this->assertSame('foo', $routeVisits[1]->getRoute());
        $this->assertSame('Ostrolucky\TraceRoute\Tests\Fixtures\Controller\TestController::fooAction', $routeVisits[1]->getController());
        $this->assertSame(1, $routeVisits[1]->getVisitCount());

        $client->request('GET', '/not-existing');
        $this->assertSame(404, $client->getResponse()->getStatusCode());
        $this->assertCount(2, $em->getRepository(RouteVisit::class)->findAll());
    }
}
