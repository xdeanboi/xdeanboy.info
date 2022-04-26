<?php

namespace xDeanBoy\Models\Users;

use xDeanBoy\Models\ActiveRecordEntity;

class UserRoles extends ActiveRecordEntity
{
    protected $name;

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'user_roles';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}