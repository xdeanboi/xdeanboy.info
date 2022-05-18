<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Models\ActiveRecordEntity;

class BookLanguage extends ActiveRecordEntity
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
        return 'book_language';
    }

    /**
     * @param string $language
     * @return static|null
     */
    public static function getByName(string $language): ?self
    {
        $result = self::findOneByColumn('name', $language);

        return !empty($result) ? $result : null;
    }
}