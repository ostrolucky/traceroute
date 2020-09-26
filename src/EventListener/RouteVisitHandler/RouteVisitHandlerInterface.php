<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\EventListener\RouteVisitHandler;

use Ostrolucky\TraceRoute\Model\RouteVisit;

interface RouteVisitHandlerInterface
{
    public function handle(RouteVisit $routeVisit): void;
}
