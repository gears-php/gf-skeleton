gf-skeleton
===========

This is a sample skeleton application built with Gears PHP framework. Use it as a basis for starting your own great projects.
The main app creation principles are explained at below topics.

### 0. Project structure


### 1. The minimal configuration
```yaml
# app/config/routes.yml
- route: /*
  to: /
```
Above is the default routing rule


### 2. Your project entry point file
```php
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
```
