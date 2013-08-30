<?php # web/index.php

use Gears\Framework\App\App;
use Gears\Framework\App\Exception\ResourceNotFound;

// framework bootstrapper file
require_once '../vendor/gears-php/framework/bootstrap.php';
require_once '../vendor/autoload.php';

try {
    (new App)->run();
} catch (ResourceNotFound $e) {
    throw $e;
}