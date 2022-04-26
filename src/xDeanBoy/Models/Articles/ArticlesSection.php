<?php

namespace xDeanBoy\Models\Articles;

use xDeanBoy\Models\ActiveRecordEntity;

class ArticlesSection extends ActiveRecordEntity
{
    protected $section;

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'articles_section';
    }

    /**
     * @param string $section
     */
    public function setSection(string $section): void
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }
}