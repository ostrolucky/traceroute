<?php

use Symfony\Component\ErrorHandler\ErrorHandler;

require_once dirname(__DIR__).'/vendor/autoload.php';

ErrorHandler::register(null, false);