<?php

namespace Controllers;

use Gears\Framework\App\Controller;
use Gears\Framework\Cache\File;
use Gears\Framework\View\View;

/**
 * Hello %username% demo controller
 */
class HelloController extends Controller
{
    public function welcomeAction()
    {
        $view = new View([
            'templates' => APP_PATH . 'views',
            'cache' => new File(ROOT_PATH . 'cache', ['noExpire' => true])
        ]);
        echo $view->load('welcome')
            ->assign('username', 'John Doe')
            ->render();
    }
}