<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Db\Db;

class BooksAndAuthors
{
    private $bookId;
    private $authorId;

    private static function getTableName():string
    {
        return 'books_and_authors';
    }

    /**
     * @param int $bookId
     */
    public function setBookId(int $bookId): void
    {
        $this->bookId = $bookId;
    }

    /**
     * @return int
     */
    public function getBookId(): int
    {
        return $this->bookId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
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
     * @param int $authorId
     * @return array|null
     */
    public static function getBooksByAuthorId(int $authorId): ?array
    {
        $db = Db::getInstance();

        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE author_id = :author_id;';
        $booksAndAuthors = $db->query($sql,
            [':author_id' => $authorId], self::class);


        if ($booksAndAuthors === []) {
            return null;
        }

        $result = [];

        foreach ($booksAndAuthors as $bookAndAuthor) {
            $result[] = Books::getById($bookAndAuthor->getBookId());
        }

        if ($result === []) {
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
        $db = Db::getInstance();

        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE book_id = :book_id;';

        $authorsId = $db->query($sql, [':book_id' => $bookId], self::class);

        if ($authorsId === []) {
            return null;
        }


        $authors = [];
        foreach ($authorsId as $authorId) {
            $authors[] = BookAuthors::getById($authorId->getAuthorId());
        }

        return $authors;
    }
}