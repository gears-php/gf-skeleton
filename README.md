gf-skeleton
===========

This is a sample skeleton application built with Gears PHP framework. Use it as a basis for starting your own great projects.
The main app principles are explained at below topics.

### 0. Recommended structure


### 1. The minimal configuration
```yaml
# app/config/routes.yml
- route: /*
  to: /
```
Above is the default routing rule


### 2. You project entry point file
```php
<?php # index.php

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
```
