<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests\Fixtures\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Ostrolucky\TraceRoute\Bridge\Symfony\TraceRouteBundle;
use Ostrolucky\TraceRoute\Tests\Fixtures\Controller\TestController;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    private $projectDir = null;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [new FrameworkBundle(), new DoctrineBundle(), new TraceRouteBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(static function (ContainerBuilder $container): void {
            $container->loadFromExtension('framework', [
                'test' => true,
                'router' => ['resource' => __DIR__.'/../Controller', 'type' => 'annotation'],
            ]);
            $container->loadFromExtension('doctrine', [
                'dbal' => ['url' => 'sqlite:///:memory:'],
                'orm' => [],
            ]);
            $container->register(TestController::class)->setPublic(true);
            // Register a NullLogger to avoid getting the stderr default logger of FrameworkBundle
            $container->register('logger', NullLogger::class);
        });
    }

    public function getProjectDir(): string
    {
        if ($this->projectDir === null) {
            $this->projectDir = sys_get_temp_dir().'/sf_kernel_'.md5((string)mt_rand());
        }

        return $this->projectDir;
    }

    public function getRootDir(): string
    {
        return $this->getProjectDir();
    }
}
