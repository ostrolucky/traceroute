<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests\Fixtures\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function indexAction(): Response
    {
        return new Response('index');
    }

    public function fooAction(): Response
    {
        return new Response('foo');
    }
}
