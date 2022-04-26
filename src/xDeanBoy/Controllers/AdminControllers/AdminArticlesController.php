<?php

namespace xDeanBoy\Controllers\AdminControllers;

use xDeanBoy\Controllers\AbstractController;
use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Exceptions\UnauthorizedException;
use xDeanBoy\Models\Articles\Article;
use xDeanBoy\Models\Articles\ArticleOffered;
use xDeanBoy\Models\Articles\ArticlesSection;
use xDeanBoy\Models\Users\User;

class AdminArticlesController extends AbstractController
{
    /**
     * @return void
     * @throws ForbiddenException
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function view():void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Заборонений доступ');
        }

        $articlesOffered = ArticleOffered::findAll();

        $articles = Article::findAll();

        if (empty($articles)) {
            throw new InvalidArgumentException('Статей не знайдено');
        }

        $articlesSection = ArticlesSection::findAll();

        if (empty($articlesSection)) {
            throw new InvalidArgumentException('Розділи статей не знайдені');
        }

        if (!empty(array_filter($_POST))) {

            $filteredArticles = [];

            if (!empty($_POST['filterByLastDays'])) {
                try {
                    if (!is_numeric($_POST['filterByLastDays'])) {
                        throw new InvalidArgumentException('Фільтр днів може бути тільки числовим значенням');
                    }

                    foreach ($articles as $article) {
                        $differenceDays = time() - strtotime($article->getCreatedAt());

                        if ($_POST['filterByLastDays'] >= date('z', $differenceDays)) {
                            $filteredArticles['filteredByLastDays'][] = $article;
                        }
                    }
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('admin/articles.php',
                        ['title' => 'Адмінка статей',
                            'articlesSection' => $articlesSection,
                            'articlesOffered' => $articlesOffered,
                            'errorFilter' => $e->getMessage()]);
                    return;
                }
            }

            if (!empty($_POST['filterBySection'])) {
                $section = ArticlesSection::findOneByColumn('section', $_POST['filterBySection']);

                if (!empty($section)) {
                    $filteredArticles['filteredBySection'] = Article::findAllByColumn('section_id', $section->getId());
                }
            }

            if (!empty($_POST['filterByAuthor'])) {
                $author = User::findOneByColumn('nickname', $_POST['filterByAuthor']);

                if (!empty($author)) {
                    $filteredArticles['filteredByAuthor'] = Article::findAllByColumn('author_id', $author->getId());
                }

            }

            $this->view->renderHtml('admin/articles.php',
            ['title' => 'Адмінка статей',
                'articlesSection' => $articlesSection,
                'filteredArticles' => $filteredArticles]);
            return;
        }


        $this->view->renderHtml('admin/articles.php',
        ['title' => 'Адмінка статей',
            'articlesSection' => $articlesSection,
            'articlesOffered' => $articlesOffered]);
    }

    /**
     * @param int $idOfferedArticle
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function deleteOfferedArticle(int $idOfferedArticle): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('У вас не має доступу');
        }

        $articleOffered = ArticleOffered::getById($idOfferedArticle);

        if (empty($articleOffered)) {
            throw new NotFoundException('Запропонованої статті не знайдено');
        }

        $articleOffered->delete();

        header('Location: /admin/articles', true, 302);
        return;
    }

    /**
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function create(): void
    {
        if(empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException();
        }

        $articleSections = ArticlesSection::findAll();

        if (empty($articleSections)) {
            throw new NotFoundException('Розділи статей не знайдені');
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createArticle($_POST, $this->user);

                header('Location: /articles/' . $article->getId(), true, 302);
                return;
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('admin/articleCreate.php',
                ['title' => 'Помилка',
                    'articleSections' => $articleSections,
                    'error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('admin/articleCreate.php',
        ['title' => 'Створення статті',
            'articleSections' => $articleSections]);
    }
}