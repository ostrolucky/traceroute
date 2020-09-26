<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Bridge\Symfony;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TraceRouteBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        if (!class_exists(DoctrineOrmMappingsPass::class)) {
            return;
        }

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createPhpMappingDriver(
                [__DIR__.'/../../../src/Database/Mapping/orm' => 'Ostrolucky\\TraceRoute\\Model']
            )
        );
    }
}
