<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Command\UnusedRouteCollector;

use Ostrolucky\TraceRoute\Database\Repository\RepositoryInterface;

abstract class AbstractUnusedRouteCollector
{
    private $repository;

    /**
     * @return array<string>
     */
    abstract protected function collectAllRoutes(): iterable;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return iterable<string>
     */
    public function collectUnusedRoutes(): iterable
    {
        $usedRoutes = [];
        foreach ($this->repository->findAll() as $routeVisit) {
            $usedRoutes[] = $routeVisit->getRoute();
        }

        foreach ($this->collectAllRoutes() as $route) {
            if (\in_array($route, $usedRoutes, true)) {
                continue;
            }

            yield $route;
        }
    }
}
