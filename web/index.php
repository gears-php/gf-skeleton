<?php # web/index.php

use Gears\Framework\App\Exception\ResourceNotFound;
use Gears\Framework\App\Bootstrap;

require_once '../vendor/autoload.php';

try {
    /** @var Bootstrap $bootstrap */
    $bootstrap = require_once '../vendor/gears-php/framework/src/bootstrap.php';
    $bootstrap->run();
} catch (ResourceNotFound $e) {
    throw $e; // 404 page for prod environment
}
