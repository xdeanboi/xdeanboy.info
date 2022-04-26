<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Exceptions\ForbiddenException;

class VideoController extends AbstractController
{
    public function viewAll(): void
    {
        if (empty($this->user)) {
            throw new ForbiddenException('Авторизуйтеся');
        }

        $this->view->renderHtml('video/videoAll.php', ['title' => 'Відео']);
    }
}