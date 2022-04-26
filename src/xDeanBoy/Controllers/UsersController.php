<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Models\Users\Profiles;
use xDeanBoy\Models\Users\ProfileSocial;
use xDeanBoy\Models\Users\User;
use xDeanBoy\Services\EmailSender;
use xDeanBoy\Services\UserActivateServices;
use xDeanBoy\Services\UserAuthServices;

class UsersController extends AbstractController
{
    /**
     * @return void
     * @throws ForbiddenException
     */
    public function login()
    {
        if (!empty($this->user)) {
            throw new ForbiddenException('Ви вже авторизовані');
        }

        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);

                if (!empty($user)) {
                    UserAuthServices::setAuthTokenForLogin($user);
                }

            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php',
                    ['title' => 'Error',
                        'error' => $e->getMessage()]);
                return;
            }

            header('Location: /users/' . $user->getId() . '/profile');
            return;
        }

        $this->view->renderHtml('users/login.php');
    }

    /**
     * @return void
     */
    public function loginOut(): void
    {
        User::loginOut();

        header('Location:/', true, 302);
        return;
    }

    /**
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function register(): void
    {
        if (!empty($this->user)) {
            throw new ForbiddenException('Неможливо зареєструвати користувача. Ви вже авторизовані');
        }

        if (!empty($_POST)) {
            try {
                $user = User::register($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/register.php',
                    ['title' => 'Error',
                        'error' => $e->getMessage()]);
                return;
            }

            if (!empty($user)) {
                if ($user instanceof User) {
                    Profiles::createProfile($user);

                    $activationCode = UserActivateServices::createActivationCode($user);

                    if (!empty($activationCode)) {

                        EmailSender::send(
                            $user,
                            'Активація користувача',
                            'userActivate.php',
                            ['nickname' => $user->getNickname(),
                                'userId' => $user->getId(),
                                'code' => $activationCode
                            ]);

                    }
                }
            }

            $this->view->renderHtml('users/registerSuccessful.php',
                ['title' => 'Успішна реєстрація',
                    'nickname' => $user->getNickname()
                ]);
            return;
        }

        $this->view->renderHtml('users/register.php');
    }

    /**
     * @param int $userId
     * @param string $code
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function activation(int $userId, string $code): void
    {
        if (!empty($this->user)) {
            throw new ForbiddenException('Ви вже активовані');
        }

        try {
            $user = User::getById($userId);

            if (empty($user)) {
                throw new NotFoundException('Користувача не найдено');
            }

            $checkActivationCode = UserActivateServices::checkActivationCode($user->getId(), $code);

            if (!$checkActivationCode) {
                throw new InvalidArgumentException('Некоректний код');
            }

            $user->activate();

            if (!$user->getIsConfirmed()) {
                throw new InvalidArgumentException('Помилка активації');
            }

            UserActivateServices::deleteActivationCode($user, $code);
        } catch (InvalidArgumentException $e) {
            $this->view->renderHtml('errors/errorUserActivation.php',
                ['title' => 'Error',
                    'error' => $e->getMessage()]);
            return;
        }

        $this->view->renderHtml('users/activateSuccessful.php',
            ['title' => 'Успішна активація',
                'nickname' => $user->getNickname()]);
    }

    /**
     * @param int $userId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function profile(int $userId): void
    {
        if (empty($this->user)) {
            throw new ForbiddenException('Авторизуйтеся');
        }

        $user = User::getById($userId);

        if (empty($user)) {
            throw new NotFoundException('Користувача не знайдено');
        }

        $profile = Profiles::getProfileUser($userId);

        /*Social links start*/
        $profilesSocial = ProfileSocial::getSocialsByUser($userId);
        $socialLinks = [];
        if (!empty($profilesSocial)) {
            foreach ($profilesSocial as $profileSocial) {
               $socialLinks[$profileSocial->getSocial()] = $profileSocial->getSocialLink();
            }
        }
        /*Social links end*/

        // roleClass and role for classStyles start
        $roleClass = 'role_user';
        if ($user->isAdmin()) {
            $roleClass = 'role_admin';
        } elseif ($user->isModerator()) {
            $roleClass = 'role_mod';
        }
        // roleClass for classStyles end


        $this->view->renderHtml('users/profile.php',
            ['title' => 'Профіль ' . $user->getNickname(),
                'thisUser' => $this->user,
                'user' => $user,
                'profile' => $profile,
                'roleClass' => $roleClass,
                'socialLinks' => $socialLinks]);
    }

    /**
     * @param int $userId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function editProfile(int $userId): void
    {
        if (empty($this->user)) {
            throw new ForbiddenException('Авторизуйтеся');
        }

        $user = User::getById($userId);

        if (empty($user)) {
            throw new NotFoundException('Користувача не знайдено');
        }

        if (!$this->user->isAdmin()) {
            if ($this->user->getId() !== $user->getId()) {
                throw new ForbiddenException('Заборонений доступ');
            }
        }

        $profile = Profiles::getProfileUser($userId);

        // roleClass and role for classStyles start
        $roleClass = 'role_user';
        $role = 'Користувач';
        if ($user->isAdmin()) {
            $roleClass = 'role_admin';
            $role = 'Адміністратор';
        } elseif ($user->isModerator()) {
            $roleClass = 'role_mod';
            $role = 'Модератор';
        }
        // roleClass for classStyles end

        /*Social links start*/
        $profilesSocial = ProfileSocial::getSocialsByUser($userId);

        $socialNicknames = [];
        if (!empty($profilesSocial)) {
            foreach ($profilesSocial as $profileSocial) {
                if (!empty($_POST['links'])) {
                    foreach (array_keys(array_filter($_POST['links'])) as $socialLink) {
                        if (!empty(ProfileSocial::getSocialByUserAndLink($profileSocial->getUserId(), $socialLink))) {
                            $profileSocial->editSocialsForProfile($userId, $_POST['links']);
                        } else {
                            ProfileSocial::createSocialsForProfile($userId, $socialLink, $_POST['links'][$socialLink]);
                        }
                    }
                }

                $socialNicknames[$profileSocial->getSocial()] = $profileSocial->getNickname();
            }
        }
        /*Social links end*/

        /*Edit profile start*/
        if (!empty($_POST)) {
            $profile->editProfile($_POST);

            header('Location: /users/' . $userId . '/profile');
            return;
        }
        /*Edit profile end*/

        $this->view->renderHtml('users/profileEdit.php',
            ['title' => 'Редагування профіля',
                'user' => $user,
                'roleClass' => $roleClass,
                'role' => $role,
                'profile' => $profile,
                'socialNicknames' => $socialNicknames]
        );
    }
}