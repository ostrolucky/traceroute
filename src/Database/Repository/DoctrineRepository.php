<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Database\Repository;

use Doctrine\Persistence\ObjectManager;
use Ostrolucky\TraceRoute\Model\RouteVisit;

class DoctrineRepository implements RepositoryInterface
{
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function save(RouteVisit $routeVisit): void
    {
        $this->objectManager->persist($routeVisit);
        $this->objectManager->flush();
    }

    public function findOneByRouteVisit(RouteVisit $routeVisit): ?RouteVisit
    {
        return $this->objectManager->getRepository(RouteVisit::class)->findOneBy(
            ['route' => $routeVisit->getRoute(), 'method' => $routeVisit->getMethod()]
        );
    }

    public function findAll(): iterable
    {
        return $this->objectManager->getRepository(RouteVisit::class)->findAll();
    }
}
