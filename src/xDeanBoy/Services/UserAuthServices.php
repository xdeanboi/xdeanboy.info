<?php

namespace xDeanBoy\Services;

use xDeanBoy\Models\Users\User;

class UserAuthServices
{

    /**
     * @param User $user
     * @return void
     */
    public static function setAuthTokenForLogin(User $user): void
    {
        $token = $user->getId() . ':' . $user->getAuthToken();
        setcookie('token', $token, 0, '/', '', false, true);
    }


    /**
     * @return User
     */
    public static function getUserByToken(): ?User
    {
        $token = $_COOKIE['token'] ?? '';

        if(empty($token)) {
            return null;
        }

        [$userId, $authToken] = explode(':', $token);

        $user = User::getById($userId);

        if (empty($user)) {
            return null;
        }

        if ($user->getAuthToken() !== $authToken) {
            return null;
        }

        return $user;
    }


}