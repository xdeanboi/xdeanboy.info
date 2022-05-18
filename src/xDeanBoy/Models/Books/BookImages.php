<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Models\ActiveRecordEntity;

class BookImages extends ActiveRecordEntity
{
    protected $link;

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'book_images';
    }
}