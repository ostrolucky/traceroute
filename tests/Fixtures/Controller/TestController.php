<?php

declare(strict_types=1);

namespace Ostrolucky\TraceRoute\Tests\Fixtures\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Route("/")
     */
    public function indexAction(): Response
    {
        return new Response('index');
    }

    /**
     * @Route("/foo", name="foo")
     */
    public function fooAction(): Response
    {
        return new Response('foo');
    }
}
