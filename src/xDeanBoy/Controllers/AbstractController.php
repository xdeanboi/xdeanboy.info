<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Services\UserAuthServices;
use xDeanBoy\View\View;

abstract class AbstractController
{
    protected $view;
    protected $user;

    public function __construct()
    {
        //connect templates
        $this->view = new View(__DIR__ . '/../../../templates');

        // get user by token
        $this->user = UserAuthServices::getUserByToken();
        $this->view->setVars('thisUser', $this->user);
    }
}