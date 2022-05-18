<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Models\ActiveRecordEntity;

class Books extends ActiveRecordEntity
{
    protected $name;
    protected $description;
    protected $createdAt;

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
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return BookCharacteristic
     */
    public function getCharacteristic(): BookCharacteristic
    {
        return BookCharacteristic::getById($this->getId());
    }

    /**
     * @return string|null
     */
    public function getImageLink(): ?string
    {
        $link = BookImages::getById($this->getId());
        return !empty($link) ? $link->getLink() : null;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'books';
    }

    /**
     * @param string $name
     * @return static|null
     */
    public static function getByName(string $name): ?self
    {
        $result = self::findOneByColumn('name', $name);

        return !empty($result) ? $result : null;
    }

    /**
     * @param string $genre
     * @return array|null
     */
    public static function getByGenre(string $genre): ?array
    {
        $bookGenre = BookGenre::getByName($genre);

        if (empty($bookGenre)) {
            return null;
        }

        $booksCharacteristic = BookCharacteristic::findAllByColumn('genre_id', $bookGenre->getId());

        if (empty($booksCharacteristic)) {
            return null;
        }

        $booksId = [];
        foreach ($booksCharacteristic as $bookCharacteristic) {
            $booksId[] = $bookCharacteristic->getId();
        }

        if ($booksId === []) {
            return null;
        }

        $result = [];

        foreach ($booksId as $bookId) {
            $result[] = self::getById($bookId);
        }

        if ($result === []) {
            return null;
        }

        return $result;
    }

    /**
     * @param string $language
     * @return array|null
     */
    public static function getByLanguage(string $language): ?array
    {
        $bookLanguage = BookLanguage::getByName($language);

        if (empty($bookLanguage)) {
            return null;
        }

        $booksCharacteristic = BookCharacteristic::findAllByColumn('language_id', $bookLanguage->getId());

        if (empty($booksCharacteristic)) {
            return null;
        }

        $result = [];
        foreach ($booksCharacteristic as $bookCharacteristic) {
            $result[] = self::getById($bookCharacteristic->getId());
        }

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param int $authorId
     * @return array|null
     */
    public static function getByAuthorId(int $authorId): ?array
    {
        $result = BooksAndAuthors::getBooksByAuthorId($authorId);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param int $bookId
     * @return array|null
     */
    public static function getAuthorsBook(int $bookId): ?array
    {
        $result = BooksAndAuthors::getAuthorsBook($bookId);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param string $surname
     * @return array|null
     */
    public static function getByAuthorsSurname(string $surname): ?array
    {
        $authors = BookAuthors::getByAllSurname($surname);

        if (empty($authors)) {
            return null;
        }

        foreach ($authors as $author) {
            $result = self::getByAuthorId($author->getId());
        }

        if (empty($result)) {
            return null;
        }

        return $result;
    }


    /**
     * @param string $name
     * @return array|null
     */
    public static function getByAuthorsName(string $name): ?array
    {
        $authors = BookAuthors::getAllByName($name);

        if (empty($authors)) {
            return null;
        }

        foreach ($authors as $author) {
            $result[] = self::getByAuthorId($author->getId());
        }

        if (empty($result)) {
            return null;
        }

        $filterResult = array_filter($result);

        return $filterResult;
    }

    /**
     * @param int $pageCount
     * @return array|null
     */
    public static function getByPage(int $pageCount): ?array
    {
        $allBooks = Books::findAll();

        if (!empty($allBooks)) {
            $books = [];
            foreach ($allBooks as $book) {
                $bookCharacteristic = BookCharacteristic::getById($book->getId());

                if (empty($bookCharacteristic)) {
                    return null;
                }


                if ($bookCharacteristic->getPages() <= $pageCount) {
                    $books[] = $book;
                }
            }

            return $books;
        }

        return null;
    }

    /**
     * @param int $year
     * @return array|null
     */
    public static function getByYear(int $year): ?array
    {
        $characteristics = BookCharacteristic::findAllByColumn('year', $year);

        if (!empty($characteristics)) {
            $result = [];
            foreach ($characteristics as $characteristic) {
                $result[] = Books::getById($characteristic->getId());
            }

            return $result;
        }

        return null;
    }
}