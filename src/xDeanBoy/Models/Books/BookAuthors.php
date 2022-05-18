<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Models\ActiveRecordEntity;

class BookAuthors extends ActiveRecordEntity
{
    protected $surname;
    protected $name;

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

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
    public function getFullName(): string
    {
        return $this->getSurname() . ' ' . $this->getName();
    }

    /**
     * @param string $surname
     * @param string $name
     * @return void
     */
    public function setFullName(string $surname, string $name): void
    {
        $this->surname = $surname;
        $this->name = $name;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'book_authors';
    }

    /**
     * @param string $authorName
     * @return array|null
     */
    public static function getAllByName(string $authorName): ?array
    {
        $result = self::findAllByColumn('name', $authorName);

        return !empty($result) ? $result : null;
    }

    /**
     * @param string $authorSurname
     * @return self|null
     */
    public static function getByAllSurname(string $authorSurname): ?array
    {
        $result = self::findAllByColumn('surname', $authorSurname);

        return !empty($result) ? $result : null;
    }

    /**
     * @return array|null
     */
    public static function findAllNameAuthors(): ?array
    {
        $allAuthors = self::findAll();

        if (!empty($allAuthors)) {
            $result = [];

            foreach ($allAuthors as $author) {
                $result[] = $author->getName();
            }

            if ($result === []) {
                return null;
            }

            return $result;
        }

        return null;
    }

    /**
     * @return array|null
     */
    public static function findAllSurnameAuthors(): ?array
    {
        $allAuthors = self::findAll();

        if (!empty($allAuthors)) {
            $result = [];

            foreach ($allAuthors as $author) {
                $result[] = $author->getSurname();
            }

            if ($result === []) {
                return null;
            }

            return $result;
        }

        return null;
    }

}