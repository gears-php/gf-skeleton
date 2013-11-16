gf-skeleton
===========

This is a sample skeleton application built with Gears PHP framework. Use it as a basis for starting your own great projects.
The main app creation principles are explained at below topics.

### 0. Project structure

```yaml
app/
	config/
		app.yml
		routes.yml
	controllers/
	models/
	views/
	Bootstrap.php
vendor/
web/
	index.php
```

### 1. The minimal (mandatory) configuration

Routes configuration file should contain a list of routing rules to be used for incoming request URI matching and further successful dispatching to an appropriate controller/action. Below example describes a single "default" routing rule which is used in the current skeleton application:

```yaml
# app/config/routes.yml
- route: /*
  to: /
```

### 2. The project entry point file
```php
<?php # web/index.php

use Gears\Framework\App\Exception\ResourceNotFound;
use Gears\Framework\App\Bootstrap;

require_once '../vendor/autoload.php';

try {
    /** @var Bootstrap $bootstrap */
    $bootstrap = require_once '../vendor/gears-php/framework/bootstrap.php';
    $bootstrap->run();
} catch (ResourceNotFound $e) {
    throw $e; // 404 page for prod environment
}

```
