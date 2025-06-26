<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests\Fixtures\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\Tools\SchemaTool;
use Ostrolucky\TraceRoute\Bridge\Symfony\TraceRouteBundle;
use Ostrolucky\TraceRoute\Tests\Fixtures\Controller\TestController;
use Psr\Log\NullLogger;
use ReflectionObject;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

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
                'router' => ['resource' => 'kernel::loadRoutes', 'type' => 'service', 'utf8' => true],
            ]);
            $container->loadFromExtension('doctrine', [
                'dbal' => ['url' => 'sqlite:///:memory:'],
                'orm' => [],
            ]);
            $container->register(TestController::class)->setPublic(true);
            // Register a NullLogger to avoid getting the stderr default logger of FrameworkBundle
            $container->register('logger', NullLogger::class);
            $container->register('kernel', static::class)
                ->addTag('routing.route_loader')
                ->setAutoconfigured(true)
                ->setSynthetic(true)
                ->setPublic(true);
        });
    }

    public function loadRoutes(LoaderInterface $loader): RouteCollection
    {
        $file         = (new ReflectionObject($this))->getFileName();
        $kernelLoader = $loader->getResolver()->resolve($file, 'php');
        assert($kernelLoader instanceof PhpFileLoader);
        $kernelLoader->setCurrentDir(dirname($file));

        $collection = new RouteCollection();
        $collection->add('home', new Route('/', ['_controller' => TestController::class.'::indexAction']));
        $collection->add('foo', new Route('/foo', ['_controller' => TestController::class.'::fooAction']));;

        return $collection;
    }

    public function boot(): void
    {
        parent::boot();

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        (new SchemaTool($em))->updateSchema($em->getMetadataFactory()->getAllMetadata());
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
