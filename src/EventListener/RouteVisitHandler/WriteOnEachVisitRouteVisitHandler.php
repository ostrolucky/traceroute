<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\EventListener\RouteVisitHandler;

use Ostrolucky\TraceRoute\Database\Repository\RepositoryInterface;
use Ostrolucky\TraceRoute\Model\RouteVisit;

class WriteOnEachVisitRouteVisitHandler implements RouteVisitHandlerInterface
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(RouteVisit $routeVisit): void
    {
        if (!$persistedRouteVisit = $this->repository->findOneByRouteVisit($routeVisit)) {
            $this->repository->save($routeVisit);

            return;
        }

        $persistedRouteVisit->increaseVisitCount();
        $this->repository->save($persistedRouteVisit);
    }
}
