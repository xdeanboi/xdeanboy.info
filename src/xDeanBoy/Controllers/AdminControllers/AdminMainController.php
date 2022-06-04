<?php

namespace xDeanBoy\Controllers\AdminControllers;

use xDeanBoy\Controllers\AbstractController;
use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\UnauthorizedException;
use xDeanBoy\Models\Articles\Article;
use xDeanBoy\Models\Articles\ArticlesSection;
use xDeanBoy\Models\Books\BookGenre;
use xDeanBoy\Models\Books\Books;
use xDeanBoy\Models\Users\User;

class AdminMainController extends AbstractController
{
    /**
     * @return void
     * @throws ForbiddenException
     * @throws InvalidArgumentException
     * @throws UnauthorizedException
     */
    public function statistics(): void
    {
        if(empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Доступ заборонено');
        }

        $articles = Article::findAll();
        $articlesSection = ArticlesSection::findAll();

        if (empty($articles)) {
            throw new InvalidArgumentException('Статей не знайдено');
        }

        if (empty($articlesSection)) {
            throw new InvalidArgumentException('Розділи не знайдені');
        }

        $users = User::findAll();

        if (empty($users)) {
            throw new InvalidArgumentException('Користувачів не знайдено');
        }

        //Count by Last week, day start
        $lastWeek = [];
        $lastDay = [];

        //Sections start
        $sections = [];
        $sectionsCount = [];

        foreach ($articles as $article) {
            $differenceDate = time() - strtotime($article->getCreatedAt());

            if (date('U', $differenceDate) <= (60*60*24*7)) {
                $lastWeek['articles'][] = $article;
            }
            $lastWeek['articles'][] = null;

            if (date('U', $differenceDate) <= (60*60*24)) {
                $lastDay['articles'][] = $articles;
            }
            $lastDay['articles'][] = null;

            $sections[$article->getSection()->getSection()][] = $article;
            $sectionsCount[$article->getSection()->getSection()] = count($sections[$article->getSection()->getSection()]);
        }
        //Sections end

        //books

        $books = Books::findAll();
        $genresBooks = BookGenre::findAll();

        if (empty($books)) {
            throw new InvalidArgumentException('Книг не знайдено');
        }

        if (empty($genresBooks)) {
            throw new InvalidArgumentException('Жанри книг не знайдено');
        }

        $genres = [];
        $genresCount = [];

        foreach ($books as $book) {
            $differenceDate = time() - strtotime($book->getCreatedAt());

            if (date('U', $differenceDate) <= (60*60*24*7)) {
                $lastWeek['books'][] = $book;
            }

            $lastWeek['books'][] = null;

            if (date('U', $differenceDate) <= (60*60*24)) {
                $lastDay['books'][] = $book;
            }

            $lastDay['books'][] = null;

            $genres[$book->getCharacteristic()->getGenre()][] = $book;
            $genresCount[$book->getCharacteristic()->getGenre()] = count($genres[$book->getCharacteristic()->getGenre()]);
        }

        //users

        $roles = [];
        $rolesCount = [];
        foreach ($users as $user) {
            $differenceDate = time() - strtotime($user->getCreatedAt());

            if (date('U', $differenceDate) <= (60*60*24*7)) {
                $lastWeek['users'][] = $user;
            }
            $lastWeek['users'][] = null;

            if (date('U', $differenceDate) <= (60*60*24)) {
                $lastDay['users'][] = $user;
            }
            $lastDay['users'][] = null;

            $roles[$user->getRole()][] = $user;
            $rolesCount[$user->getRole()] = count($roles[$user->getRole()]);
        }
        $rolesName = array_keys($roles);

        //Count by Last week, day end

        $statistics = [];

        //articles
        $statistics['articles']['count'] = count($articles);
        $statistics['articles']['lastWeek'] = count(array_filter($lastWeek['articles']));
        $statistics['articles']['lastDay'] = count(array_filter($lastDay['articles']));
        $statistics['articles']['sections'] = $sectionsCount;

        //book
        $statistics['books']['count'] = count($books);
        $statistics['books']['lastWeek'] = count(array_filter($lastWeek['books']));
        $statistics['books']['lastDay'] = count(array_filter($lastDay['books']));
        $statistics['books']['genres'] = $genresCount;

        //video

        //users
        $statistics['users']['count'] = count($users);
        $statistics['users']['lastWeek'] = count(array_filter($lastWeek['users']));
        $statistics['users']['lastDay'] = count(array_filter($lastDay['users']));
        $statistics['users']['roles'] = $rolesCount;

        $this->view->renderHtml('admin/statistics.php',
        ['title' => 'Статистика',
            'statistics' => $statistics,
            'articlesSection' => $articlesSection,
            'booksGenres' => $genresBooks,
            'roles' => $rolesName]);
    }
}
