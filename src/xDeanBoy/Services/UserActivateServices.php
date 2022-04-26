<?php

namespace xDeanBoy\Services;

use xDeanBoy\Db\Db;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Models\Users\User;

class UserActivateServices
{
    private const TABLE_NAME = 'activation_user_service';

    /**
     * @param User $user
     * @return string
     * @throws NotFoundException
     */
    public static function createActivationCode(User $user): string
    {
        if (empty($user)) {
            throw new NotFoundException('User not found');
        }

        $code = md5(random_bytes(50));

        $db = Db::getInstance();
        $db->query('INSERT INTO ' . self::TABLE_NAME . ' (`user_id`, `code`) VALUES (:userId, :code);',
            [':userId' => $user->getId(),
                'code' => $code],
            static::class);

        return $code;
    }

    /**
     * @param int $userId
     * @param string $code
     * @return bool
     */
    public static function checkActivationCode(int $userId, string $code): bool
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE `user_id` = :userId AND `code` = :code;',
            [':userId' => $userId,
                ':code' => $code],
            static::class);

        return !empty($result);
    }

    /**
     * @param User $user
     * @param string $code
     * @return void
     */
    public static function deleteActivationCode(User $user, string $code): void
    {
        $db = Db::getInstance();

        $sql = 'DELETE FROM ' . self::TABLE_NAME . ' WHERE user_id = :userId AND code = :code;';
        $db->query($sql,
            [':userId' => $user->getId(),
                ':code' => $code],
            static::class);
    }
}