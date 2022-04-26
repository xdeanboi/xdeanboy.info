<?php

namespace xDeanBoy\Controllers\AdminControllers;

use xDeanBoy\Controllers\AbstractController;
use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Exceptions\UnauthorizedException;
use xDeanBoy\Models\Users\User;
use xDeanBoy\Models\Users\UserRoles;

class AdminUsersController extends AbstractController
{
    /**
     * @return void
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    public function view(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('У вас не має доступу');
        }

        $users = User::findAll();
        $userRoles = UserRoles::findAll();

        $usersByFilter = [];
        $newUsers = [];

        foreach ($users as $user) {
            $differenceDateCreated = time() - strtotime($user->getCreatedAt());

            if (date('d', $differenceDateCreated) <= 1) {
                $newUsers[] = $user;
            }

            if (!empty($_POST['filterByLastDays'])) {
                try {
                    if (!is_numeric($_POST['filterByLastDays'])) {
                        throw new InvalidArgumentException('Фільтр днів може бути тільки числовим значенням');
                    }

                    if (date('z', $differenceDateCreated) <= $_POST['filterByLastDays']) {
                        $usersByFilter['filteredByLastDays'][] = $user;
                    }
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('admin/users.php',
                        ['title' => 'Адмінка користувачів',
                            'usersByFilter' => $usersByFilter,
                            'newUsers' => $newUsers,
                            'userRoles' => $userRoles,
                            'errorFilter' => $e->getMessage()]);
                    return;
                }
            }
        }

        if (!empty($_POST['filterByRole'])) {
            $role = UserRoles::findOneByColumn('name', $_POST['filterByRole']);

            if (!empty($role)) {
                $usersByFilter['filteredByRole'] = User::findAllByColumn('role_id', $role->getId());
            }
        }

        if (!empty($_POST['filterByConfirmed'])) {
            $usersByFilter['filteredByConfirmed'] = User::findAllByColumn('is_confirmed', $_POST['filterByConfirmed']);
        }

        if (!empty($_POST['filterByNickname'])) {
            $usersByFilter['filteredByNickname'] = User::findAllByColumn('nickname', $_POST['filterByNickname']);
        }

        if (!empty($_POST['filterByEmail'])) {
            $usersByFilter['filteredByEmail'] = User::findAllByColumn('email', $_POST['filterByEmail']);
        }

        $this->view->renderHtml('admin/users.php',
            ['title' => 'Адмінка користувачів',
                'usersByFilter' => $usersByFilter,
                'newUsers' => $newUsers,
                'userRoles' => $userRoles]);
    }
}