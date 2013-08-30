<?php

namespace app\controllers;

use Gears\Framework\App\Controller;
use Gears\Framework\View\View;

/**
 * Hello %username% demo controller
 */
class HelloController extends Controller
{
	public function welcomeAction()
	{
        $view = new View([APP_PATH . 'views']);
        echo $view->load('welcome')
                ->assign('username', 'John Doe')
                ->render();
	}
}