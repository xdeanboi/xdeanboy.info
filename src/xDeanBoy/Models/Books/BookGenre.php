<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Models\ActiveRecordEntity;

class BookGenre extends ActiveRecordEntity
{
    protected $name;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'book_genre';
    }

    /**
     * @param string $name
     * @return self|null
     */
    public static function getByName(string $name): ?self
    {
        $result = self::findOneByColumn('name', $name);

        return !empty($result) ? $result : null;
    }
}