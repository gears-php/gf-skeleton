gf-skeleton
===========

This is a sample skeleton application built with Gears PHP framework. Use it as a basis for starting your own great projects.
The main app creation principles are explained at below topics.

### 0. Project structure


### 1. The minimal (mandatory) configuration

Routes configuration file should contain a list of routing rules to be used for incoming request URI matching and further successfull dispatching to an appropriate controller/action

```yaml
# app/config/routes.yml
- route: /*
  to: /
```

Above is an example describing a single "default" routing rule


### 2. Your project entry point file
```php
<?php # web/index.php

use Gears\Framework\App\Exception\ResourceNotFound;

require_once '../vendor/autoload.php';

try {
    /** @var Gears\Framework\App\App $app */
    $app = require_once '../vendor/gears-php/framework/bootstrap.php';
    $app->init()->run();
} catch (ResourceNotFound $e) {
    throw $e; // 404 page for prod environment
}
```
