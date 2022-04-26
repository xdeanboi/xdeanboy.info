<?php

namespace xDeanBoy\Controllers\AdminControllers;

use xDeanBoy\Controllers\AbstractController;
use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\UnauthorizedException;
use xDeanBoy\Models\Articles\Article;
use xDeanBoy\Models\Articles\ArticlesSection;
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

            if (date('d', $differenceDate) <= 7) {
                $lastWeek['articles'][] = $article;
            }
            $lastWeek['articles'][] = null;

            if (date('d', $differenceDate) <= 1) {
                $lastDay['articles'][] = $articles;
            }
            $lastDay['articles'][] = null;

            $sections[$article->getSection()->getSection()][] = $article;
            $sectionsCount[$article->getSection()->getSection()] = count($sections[$article->getSection()->getSection()]);
        }
        //Sections end

        $roles = [];
        $rolesCount = [];
        foreach ($users as $user) {
            $differenceDate = time() - strtotime($user->getCreatedAt());

            if (date('d', $differenceDate) <= 7) {
                $lastWeek['users'][] = $user;
            }
            $lastWeek['users'][] = null;

            if (date('d', $differenceDate) <= 1) {
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
            'roles' => $rolesName]);
    }
}
