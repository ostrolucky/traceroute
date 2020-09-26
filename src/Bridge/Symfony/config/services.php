<?php

declare(strict_types=1);

use Ostrolucky\TraceRoute\Command\ShowUnusedRoutesCommand;
use Ostrolucky\TraceRoute\Command\UnusedRouteCollector\AbstractUnusedRouteCollector;
use Ostrolucky\TraceRoute\Command\UnusedRouteCollector\SymfonyUnusedRouteCollector;
use Ostrolucky\TraceRoute\Database\Repository\DoctrineRepository;
use Ostrolucky\TraceRoute\Database\Repository\RepositoryInterface;
use Ostrolucky\TraceRoute\EventListener\HttpFoundationRouteVisitEventListener;
use Ostrolucky\TraceRoute\EventListener\RouteVisitHandler\RouteVisitHandlerInterface;
use Ostrolucky\TraceRoute\EventListener\RouteVisitHandler\WriteOnEachVisitRouteVisitHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use Symfony\Component\HttpKernel\KernelEvents;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DoctrineRepository::class)->args([new ReferenceConfigurator('doctrine.orm.default_entity_manager')]);
    $services->alias(RepositoryInterface::class, DoctrineRepository::class);

    $services->set(SymfonyUnusedRouteCollector::class)->args([
        new ReferenceConfigurator(RepositoryInterface::class),
        new ReferenceConfigurator('router'),
    ]);

    $services->alias(AbstractUnusedRouteCollector::class, SymfonyUnusedRouteCollector::class);
    $services->set(ShowUnusedRoutesCommand::class)
        ->args([new ReferenceConfigurator(AbstractUnusedRouteCollector::class)])
        ->tag('console.command')
    ;

    $services->set(WriteOnEachVisitRouteVisitHandler::class)
        ->args([new ReferenceConfigurator(RepositoryInterface::class)])
    ;
    $services->alias(RouteVisitHandlerInterface::class, WriteOnEachVisitRouteVisitHandler::class);

    $services->set(HttpFoundationRouteVisitEventListener::class)
        ->args([new ReferenceConfigurator(RouteVisitHandlerInterface::class)])
        ->tag('kernel.event_listener', ['event' => KernelEvents::CONTROLLER])
    ;
};
