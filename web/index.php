<?php # web/index.php

use Gears\Framework\App\Exception\ResourceNotFound;

require_once '../vendor/autoload.php';

try {
    /** @var Gears\Framework\App\App $app */
    $app = require_once '../vendor/gears-php/framework/bootstrap.php';
    $app->run();
} catch (ResourceNotFound $e) {
    throw $e; // 404 page for prod environment
}