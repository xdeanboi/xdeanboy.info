<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Exceptions\UnauthorizedException;

class MainController extends AbstractController
{
    /**
     * @return void
     */
    public function main(): void
    {
        $this->view->renderHtml('main/main.php', ['title' => 'Головна сторінка']);
    }

    /**
     * @return void
     */
    public function aboutAuthor(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $this->view->renderHtml('main/aboutAuthor.php', ['title' => 'Про автора']);
    }
}