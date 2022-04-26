<?php

namespace xDeanBoy\Models\Users;

use xDeanBoy\Exceptions\DbException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Models\ActiveRecordEntity;

class User extends ActiveRecordEntity
{
    protected $nickname;
    protected $email;
    protected $roleId;
    protected $passwordHash;
    protected $isConfirmed;
    protected $authToken;
    protected $createdAt;

    protected static function getTableName(): string
    {
        return 'users';
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        $role = UserRoles::getById($this->getRoleId());

        return !empty($role) ? $role->getName() : null;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $userRole = UserRoles::findOneByColumn('name', $role);

        if (!empty($userRole)) {
            $this->roleId = $userRole->getId();
        }
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->getRole() === "Адмін";
    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        return $this->getRole() === 'Модератор';
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->getRole() === 'Користувач';
    }

    /**
     * @return bool
     */
    public function isVIPUser(): bool
    {
        return $this->getRole() === 'VIP Користувач';
    }

    /**
     * @return void
     */
    public function setRoleUser(): void
    {
        $roleUser = UserRoles::findOneByColumn('name', 'Користувач');

        if (!empty($roleUser)) {
            $this->roleId = $roleUser->getId();
        }
    }

    /**
     * @return void
     */
    public function setRoleAdmin(): void
    {
        $roleAdmin = UserRoles::findOneByColumn('name', 'Адмін');

        if (!empty($roleAdmin)) {
            $this->roleId = $roleAdmin->getId();
        }
    }

    /**
     * @return void
     */
    public function setRoleModerator(): void
    {
        $roleModerator = UserRoles::findOneByColumn('name', 'Модератор');

        if (!empty($roleModerator)) {
            $this->roleId = $roleModerator->getId();
        }
    }

    /**
     * @return void
     */
    public function setRoleVIPUser(): void
    {
        $roleVIPUser = UserRoles::findOneByColumn('name', 'VIP Користувач');

        if (!empty($roleVIPUser)) {
            $this->roleId = $roleVIPUser->getId();
        }
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return bool
     */
    public function getIsConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    /**
     * @param bool $isConfirmed
     */
    public function setIsConfirmed(bool $isConfirmed): void
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken(string $authToken): void
    {
        $this->authToken = $authToken;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedAtAsDate(): string
    {
        return date('d.m.y', strtotime($this->createdAt));
    }

    /**
     * @param array $usersFields
     * @return User
     * @throws InvalidArgumentException
     */
    public static function register(array $usersFields): User
    {
        if (empty($usersFields['email'])) {
            throw new InvalidArgumentException('Введіть Email');
        }

        if (!filter_var($usersFields['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Некоректний Email <br> Н-д: yourmail@gmail.com');
        }

        if (empty($usersFields['login'])) {
            throw new InvalidArgumentException('Введіть Логін');
        }

        if (!preg_match('~^[a-zA-Z0-9]+$~', $usersFields['login'])) {
            throw new InvalidArgumentException('Логін може містити тільки латинські літери та арабські цифри');
        }

        if (empty($usersFields['password1'])) {
            throw new InvalidArgumentException('Введіть Пароль');
        }

        if (mb_strlen($usersFields['password1']) < 8) {
            throw new InvalidArgumentException('Пароль має містити не менше 8 символів');
        }

        if (empty($usersFields['password2'])) {
            throw new InvalidArgumentException('Повторіть пароль');
        }

        if ($usersFields['password2'] !== $usersFields['password1']) {
            throw new InvalidArgumentException('Помилка повтору пароля');
        }

        if (static::findOneByColumn('email', $usersFields['email']) !== null) {
            throw new InvalidArgumentException('Email вже використовується');
        }

        if (static::findOneByColumn('nickname', $usersFields['login']) !== null) {
            throw new InvalidArgumentException('Логін вже зайнятий');
        }


        $user = new User();

        $user->setNickname($usersFields['login']);
        $user->setEmail($usersFields['email']);
        $user->setRoleUser();
        $user->setPasswordHash(password_hash($usersFields['password1'], PASSWORD_DEFAULT));
        $user->setIsConfirmed(false);
        $user->setAuthToken(sha1(random_bytes(100) . sha1(random_bytes(100))));

        $user->save();

        return $user;
    }

    /**
     * @return void
     */
    public function activate(): void
    {
        $this->setIsConfirmed(true);
        $this->save();
    }

    /**
     * @param array $userFields
     * @return User
     * @throws InvalidArgumentException
     */
    public static function login(array $userFields): User
    {
        if (empty($userFields['email'])) {
            throw new InvalidArgumentException('Введіть ваш email');
        }


        if (empty($userFields['password'])) {
            throw new InvalidArgumentException('Введіть ваш пароль');
        }

        $user = User::findOneByColumn('email', $userFields['email']);

        if (empty($user)) {
            throw new InvalidArgumentException('Не правильний email');
        }

        if (!password_verify($userFields['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Не правильний пароль');
        }

        $user->refreshAuthToken();
        $user->save();

        return $user;
    }


    /**
     * @return void
     * @throws \Exception
     */
    private function refreshAuthToken(): void
    {
        $this->setAuthToken(sha1(random_bytes(100)) . sha1(random_bytes(100)));
    }


    /**
     * delete $_COOKIE['token'] for user, logout
     * @return void
     */
    public static function loginOut(): void
    {
        setcookie('token', '', 0, '/', '', false, true);
    }

}