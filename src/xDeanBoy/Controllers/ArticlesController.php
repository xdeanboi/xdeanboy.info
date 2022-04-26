<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Exceptions\UnauthorizedException;
use xDeanBoy\Models\Articles\Article;
use xDeanBoy\Models\Articles\ArticleOffered;
use xDeanBoy\Models\Articles\ArticlesSection;

class ArticlesController extends AbstractController
{
    /**
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewAll(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $articles = Article::findAllByOrderArrayDesc(['edit_at', 'created_at']);

        if (empty($articles)) {
            throw new NotFoundException('Статті не знайдені');
        }

        $articlesSection = ArticlesSection::findAll();

        if (empty($articlesSection)) {
            throw new NotFoundException('Розділи не знайдені');
        }

        $this->view->renderHtml('articles/articlesAll.php',
            ['title' => 'Статті',
                'articles' => $articles,
                'articlesSection' => $articlesSection]);
    }

    /**
     * @param int $articleId
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewArticle(int $articleId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $article = Article::getById($articleId);

        if (empty($article)) {
            throw new NotFoundException('Статті не існує');
        }

        $sections = ArticlesSection::findAll();

        if (empty($sections)) {
            throw new NotFoundException('Розділи не знайдені');
        }

        $sectionByArticle = ArticlesSection::findOneByColumn('section', $article->getSection()->getSection());

        $this->view->renderHtml('articles/articleView.php',
            ['title' => 'Стаття ' . $articleId,
                'article' => $article,
                'sections' => $sections,
                'sectionByArticle' => $sectionByArticle]);
    }

    /**
     * @param string $section
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewBySection(string $section): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $allSections = ArticlesSection::findAll();

        if (empty($allSections)) {
            throw new NotFoundException('Помилка пошуку розділів');
        }

        $sectionForArticle = ArticlesSection::findOneByColumn('section', $section);

        if (empty($sectionForArticle)) {
            throw new NotFoundException('Сторінка не знайдена');
        }

        $articlesBySection = Article::findAllByColumn('section_id', $sectionForArticle->getId());

        if (empty($articlesBySection)) {
            throw new NotFoundException('Сторінка не знайдена');
        }

        $this->view->renderHtml('articles/articlesBySection.php',
            ['title' => 'Статті розділу ' . $sectionForArticle->getSection(),
                'section' => $sectionForArticle,
                'articles' => $articlesBySection,
                'allSections' => $allSections]);
    }

    /**
     * @param int $articleId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function editArticle(int $articleId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException();
        }

        $article = Article::getById($articleId);

        if (empty($article)) {
            throw new NotFoundException();
        }

        $articleSections = ArticlesSection::findAll();

        if (empty($articleSections)) {
            throw new NotFoundException('Розділи статті не знайдені');
        }

        if (!empty($_POST)) {
            try {
                $article->edit($_POST, $this->user);

                header('Location: /articles/' . $article->getId(), true, 302);
                return;
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/editArticle.php',
                    ['title' => 'Редагування статті',
                        'error' => $e->getMessage(),
                        'article' => $article,
                        'articleSections' => $articleSections]);
                return;
            }
        }

        $this->view->renderHtml('articles/editArticle.php',
            ['title' => 'Редагування статті',
                'article' => $article,
                'articleSections' => $articleSections]);
    }

    /**
     * @param int $articleId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function deleteArticle(int $articleId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Доступ заборонено');
        }

        $article = Article::getById($articleId);

        if (empty($article)) {
            throw new NotFoundException('Стаття не знайдена');
        }

        $article->delete();

        $this->view->renderHtml('articles/articlesSuccessfulDelete.php',
            ['title' => 'Успішне видалення',
                'article' => $article]);
    }

    /**
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function offeringArticle(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $articles = Article::findAll();

        if (empty($articles)) {
            throw new NotFoundException('Статті не знайдені');
        }

        $articlesSection = ArticlesSection::findAll();

        if (empty($articlesSection)) {
            throw new NotFoundException('Розділи не знайдені');
        }

        if (!empty($_POST)) {
            try {
                $articleOffered = ArticleOffered::createOffering($_POST, $this->user);

                $this->view->renderHtml('articles/articlesOffered.php',
                    ['title' => 'Успішно запропоновано',
                        'articleOffered' => $articleOffered,
                        'nickname' => $articleOffered->getUser()->getNickname()]);
                return;
            } catch (InvalidArgumentException $e) {
                $this->view->setVars('errorByOffered', $e->getMessage());
                $this->view->renderHtml('articles/articlesAll.php',
                    ['title' => 'Помилка запиту',
                        'articles' => $articles,
                        'articlesSection' => $articlesSection]);
                return;
            }
        }
    }
}