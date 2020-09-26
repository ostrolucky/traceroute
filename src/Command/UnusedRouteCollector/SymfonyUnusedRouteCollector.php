<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Command\UnusedRouteCollector;

use Ostrolucky\TraceRoute\Database\Repository\RepositoryInterface;
use Symfony\Component\Routing\RouterInterface;

class SymfonyUnusedRouteCollector extends AbstractUnusedRouteCollector
{
    private $router;

    public function __construct(RepositoryInterface $repository, RouterInterface $router)
    {
        parent::__construct($repository);
        $this->router = $router;
    }

    protected function collectAllRoutes(): iterable
    {
        return array_keys($this->router->getRouteCollection()->all());
    }
}
