<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Command;

use Ostrolucky\TraceRoute\Command\UnusedRouteCollector\AbstractUnusedRouteCollector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('ostrolucky:unused-routes')]
final class ShowUnusedRoutesCommand extends Command
{
    private $unusedRouteCollector;

    public function __construct(AbstractUnusedRouteCollector $unusedRouteCollector)
    {
        parent::__construct();

        $this->unusedRouteCollector = $unusedRouteCollector;
    }

    protected function configure(): void
    {
        $this->setDescription('Display routes that were never accessed since ostrolucky/traceroute was setup.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->unusedRouteCollector->collectUnusedRoutes() as $routeName) {
            $output->writeln($routeName);
        }

        return 0;
    }
}
