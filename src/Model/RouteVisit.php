<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Model;

class RouteVisit
{
    private $route;
    private $method;
    private $lastUpdatedAt;
    private $visitCount;
    private $controller;

    public function __construct(string $route, string $method, ?string $controller)
    {
        $this->route = $route;
        $this->method = $method;
        $this->lastUpdatedAt = new \DateTime();
        $this->visitCount = 1;
        $this->controller = $controller;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function increaseVisitCount(): void
    {
        ++$this->visitCount;
        $this->lastUpdatedAt = new \DateTime();
    }

    public function getVisitCount(): int
    {
        return $this->visitCount;
    }

    public function getLastUpdatedAt(): \DateTimeInterface
    {
        return $this->lastUpdatedAt;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }
}
