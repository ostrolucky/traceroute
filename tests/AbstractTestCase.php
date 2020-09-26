<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Ostrolucky\TraceRoute\Tests\Fixtures\DependencyInjection\TestKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractTestCase extends TestCase
{
    protected function bootKernel(): KernelInterface
    {
        $kernel = new TestKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine.orm.default_entity_manager');

        (new SchemaTool($em))->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $kernel;
    }
}
