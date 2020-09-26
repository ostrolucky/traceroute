<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\EventListener;

use Ostrolucky\TraceRoute\Model\RouteVisit;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * @extends AbstractRouteVisitEventListener<ControllerEvent>
 */
class HttpFoundationRouteVisitEventListener extends AbstractRouteVisitEventListener
{
    /**
     * @param ControllerEvent $value
     */
    protected function createRouteVisit($value): ?RouteVisit
    {
        $request = $value->getRequest();

        if (!$route = $request->get('_route')) {
            return null;
        }

        return new RouteVisit($route, $request->getMethod(), $request->get('_controller'));
    }
}
