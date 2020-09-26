<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Database\Repository;

use Ostrolucky\TraceRoute\Model\RouteVisit;

interface RepositoryInterface
{
    public function save(RouteVisit $routeVisit): void;

    public function findOneByRouteVisit(RouteVisit $routeVisit): ?RouteVisit;

    /**
     * @return iterable<RouteVisit>
     */
    public function findAll(): iterable;
}
