<?php

namespace xDeanBoy\Models\Users;

use xDeanBoy\Db\Db;

class ProfileSocial
{
    private $userId;
    private $nickname;
    private $social;

    /**
     * @return string
     */
    private static function getTableName(): string
    {
        return 'profile_social';
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
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
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getSocial(): string
    {
        return $this->social;
    }

    /**
     * @param string $social
     */
    public function setSocial(string $social): void
    {
        $this->social = $social;
    }

    /**
     * @return string
     */
    public function getSocialLink(): string
    {
        return $this->getSocial() . $this->getNickname();
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value): void
    {
        $nameToCamelCase = $this->underscoreToCamelCase($name);
        $this->$nameToCamelCase = $value;
    }

    /**
     * @param string $source
     * @return string
     */
    private function underscoreToCamelCase(string $source): string
    {
        //camel_case => camelCase
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @param int $userId
     * @return array|null
     */
    public static function getSocialsByUser(int $userId): ?array
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM ' . self::getTableName() . ' WHERE user_id = :userId;',
            [':userId' => $userId],
            self::class);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param int $userId
     * @param string $social
     * @return self|null
     */
    public static function getSocialByUserAndLink(int $userId, string $social): ?self
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM ' . self::getTableName() . ' WHERE user_id = :userId AND social = :social;',
            [':userId' => $userId,
                ':social' => $social],
            self::class);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * @return void
     */
    private function update(): void
    {
        $db = Db::getInstance();

        $sql = 'UPDATE ' . self::getTableName() . ' SET nickname = :nickname WHERE user_id = :userId AND social = :social;';
        $db->query($sql,
            [':nickname' => $this->getNickname(),
                ':userId' => $this->getUserId(),
                ':social' => $this->getSocial()],
            self::class);
    }

    /**
     * @return void
     */
    private function insert(): void
    {
        $db = Db::getInstance();

        $sql = 'INSERT INTO ' . self::getTableName() . ' (user_id, nickname, social) VALUES (:userId, :nickname, :social);';
        $db->query($sql,
            [':userId' => $this->getUserId(),
                ':nickname' => $this->getNickname(),
                ':social' => $this->getSocial()],
            self::class);

    }

    /**
     * @return void
     */
    private function delete(): void
    {
        $db = Db::getInstance();

        $sql = 'DELETE FROM ' . self::getTableName() . ' WHERE user_id = :userId AND social = :social;';
        $db->query($sql,
            [':userId' => $this->getUserId(),
                ':social' => $this->getSocial()],
            self::class);
    }

    /**
     * @param int $userId
     * @param array $socialFields
     * @return void
     */
    public function editSocialsForProfile(int $userId, array $socialFields): void
    {
        $filteredFields = array_filter($socialFields);

        switch ($this->getSocial()) {
            case 'https://t.me/':
                if ($filteredFields['https://t.me/'] !== null) {
                    $this->setNickname($filteredFields['https://t.me/']);
                    $this->update();
                } else {
                    $this->delete();
                }

                break;
            case 'https://www.instagram.com/':
                if ($filteredFields['https://www.instagram.com/'] !== null) {
                    $this->setNickname($filteredFields['https://www.instagram.com/']);
                    $this->update();
                } else {
                    $this->delete();
                }

                break;
            case 'https://www.linkedin.com/in/':
                if ($filteredFields['https://www.linkedin.com/in/'] !== null) {
                    $this->setNickname($filteredFields['https://www.linkedin.com/in/']);
                    $this->update();
                } else {
                    $this->delete();
                }

                break;
        }

    }

    /**
     * @param int $userId
     * @param string $social
     * @param string $nickname
     * @return void
     */
    public static function createSocialsForProfile(int $userId, string $social, string $nickname): void
    {
        $newSocial = new ProfileSocial();
        $newSocial->setUserId($userId);
        $newSocial->setNickname($nickname);
        $newSocial->setSocial($social);

        $newSocial->insert();
    }
}