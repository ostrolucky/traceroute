<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Ostrolucky\TraceRoute\Model\RouteVisit;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends KernelTestCase
{
    public function testListsInactiveRoutes(): void
    {
        $kernel = self::createKernel();

        $commandTester = new CommandTester((new Application($kernel))->find('ostrolucky:unused-routes'));
        $commandTester->execute([]);

        $this->assertSame("home\nfoo\n", $commandTester->getDisplay());

        /** @var EntityManagerInterface $em */
        $em = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');
        $visit = new RouteVisit('foo', 'post', null);
        $em->persist($visit);
        $em->flush();

        $commandTester->execute([]);

        $this->assertSame("home\n", $commandTester->getDisplay());
    }
}
