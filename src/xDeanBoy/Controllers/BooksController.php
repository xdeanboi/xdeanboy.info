<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Exceptions\ForbiddenException;

class BooksController extends AbstractController
{
    public function viewAll(): void
    {
        if (empty($this->user)) {
            throw new ForbiddenException('Авторизуйтеся');
        }

        $this->view->renderHtml('books/booksAll.php', ['title' => 'Книги']);
    }
}