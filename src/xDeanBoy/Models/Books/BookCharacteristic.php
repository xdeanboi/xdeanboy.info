<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Models\ActiveRecordEntity;

class BookCharacteristic extends ActiveRecordEntity
{
    protected $pages;
    protected $year;
    protected $genreId;
    protected $languageId;

    /**
     * @param int $pages
     */
    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * @return int
     */
    public function getPages(): int
    {
        return (int)$this->pages;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return (int)$this->year;
    }

    /**
     * @param int $genreId
     */
    public function setGenreId(int $genreId): void
    {
        $this->genreId = $genreId;
    }

    /**
     * @return int
     */
    public function getGenreId(): int
    {
        return (int)$this->genreId;
    }

    /**
     * @return string|null
     */
    public function getGenre(): ?string
    {
        $genre = BookGenre::getById($this->getGenreId());

        return !empty($genre) ? $genre->getName() : null;
    }

    /**
     * @param string $genreName
     * @return void
     */
    public function setGenre(string $genreName): void
    {
        $genre = BookGenre::getByName($genreName);

        if (!empty($genre)) {
            $this->setGenreId($genre->getId());
        }
    }

    /**
     * @param int $languageId
     */
    public function setLanguageId(int $languageId): void
    {
        $this->languageId = $languageId;
    }

    /**
     * @return int
     */
    public function getLanguageId(): int
    {
        return $this->languageId;
    }

    public function setLanguage(string $languageName): void
    {
        $language = BookLanguage::getByName($languageName);

        if (!empty($language)) {
            $this->setLanguageId($language->getId());
        }
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        $language = BookLanguage::getById($this->getLanguageId());

        return !empty($language) ? $language->getName() : null;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'book_characteristic';
    }
}