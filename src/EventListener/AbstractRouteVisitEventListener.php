<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\EventListener;

use Ostrolucky\TraceRoute\EventListener\RouteVisitHandler\RouteVisitHandlerInterface;
use Ostrolucky\TraceRoute\Model\RouteVisit;

/**
 * @template Event
 */
abstract class AbstractRouteVisitEventListener
{
    private $routeVisitHandler;

    /**
     * @param Event $value
     */
    abstract protected function createRouteVisit($value): ?RouteVisit;

    public function __construct(RouteVisitHandlerInterface $routeVisitHandler)
    {
        $this->routeVisitHandler = $routeVisitHandler;
    }

    /**
     * @param Event $value
     */
    public function __invoke($value): void
    {
        $routeVisit = $this->createRouteVisit($value);

        if (!$routeVisit) {
            return;
        }

        $this->routeVisitHandler->handle($routeVisit);
    }
}
