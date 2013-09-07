<?php

namespace Controllers;

use Gears\Framework\App\Controller;

/**
 * Index controller
 */
class IndexController extends Controller
{
    /**
     * Just redirecting to Welcome page
     */
    public function indexAction()
    {
        $this->redirect('hello/welcome');
    }
}