gf-skeleton
===========

This is a sample skeleton application built with Gears PHP framework. Use it as a basis for your own great projects.

The code of the current skeleton app is described at below topics.

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
# index.php
<?php

use Gears\Framework\App\App;
use Gears\Framework\App\Exception\ResourceNotFound;

// framework bootstrapper file
require_once 'path/to/vendor/gears-php/framework/bootstrap.php';

try {
	(new App)->run();
} catch (ResourceNotFound $e) {
	throw $e; // you can show 404 page instead if working at production env
}
```
