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
     * @param string $fullName
     * @return void
     */
    public function setFullName(string $fullName): void
    {
        [$surname, $name] = explode(' ', $fullName, 2);

        $this->setSurname($surname);
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getSurname() . ' ' . $this->getName();
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


    /**
     * @param string $fullName
     * @return static|null
     */
    public static function getByFullName(string $fullName): ?self
    {
        [$surname, $name] = explode(' ', $fullName, 2);

        $allSurnames = self::getByAllSurname($surname);

        if (empty($allSurnames)) {
            return null;
        }

        $idBySurnames = [];

        foreach ($allSurnames as $allSurname) {
            $idBySurnames[] = $allSurname->getId();
        }

        $allNames = self::getAllByName($name);

        if (empty($allNames)) {
            return null;
        }

        $idByNames = [];

        foreach ($allNames as $allName) {
            $idByNames[] = $allName->getId();
        }

        $checkIdByFullName = array_intersect($idBySurnames, $idByNames);

        if (empty($checkIdByFullName)) {
            return null;
        }

        $idByFullName = array_shift($checkIdByFullName);

        $author = self::getById($idByFullName);

        return !empty($author) ? $author : null;
    }
}