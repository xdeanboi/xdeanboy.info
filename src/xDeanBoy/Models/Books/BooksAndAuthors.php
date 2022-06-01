<?php

namespace xDeanBoy\Models\Books;

use xDeanBoy\Db\Db;

// For connection book with author
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
     * @param string $source
     * @return string
     */
    private function camelCaseToUnderscore(string $source): string
    {
        //camelCase => camel_case
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    /**
     * @return array
     */
    private function mapToDbProperties(): array
    {
        $reflection = new \ReflectionObject($this);
        $reflectionProperties = $reflection->getProperties();

        $mappedProperties = [];
        foreach ($reflectionProperties as $property) {
            $propertyName = $property->getName();
            $propertyNameToDb = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameToDb] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $mappedProperties = $this->mapToDbProperties();
        var_dump($mappedProperties);
        var_dump($this);
        var_dump($this->authorId !== null && $this->bookId !== null);

        if ($this->authorId !== null && $this->bookId !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    public function insert(): void
    {
        // INSERT INTO tableName (col1, col2) VALUES (:par1, :par2);
        //[:par1=>val1]

        $mappedProperties = $this->mapToDbProperties();
        $columnsToDb = [];
        $paramsToDb = [];
        $paramsToValue = [];
        $index = 1;

        foreach ($mappedProperties as $columns => $value) {
            $param = ':param' . $index++;
            $paramsToDb[] = $param;
            $columnsToDb[] = $columns;
            $paramsToValue[$param] = $value;
        }

        $columnsForSql = implode(', ', $columnsToDb);
        $paramsForSql = implode(', ', $paramsToDb);

        $db = Db::getInstance();
        $sql = 'INSERT INTO ' . self::getTableName() . ' ( ' . $columnsForSql . ' ) VALUES ( ' . $paramsForSql . ' );';

        $db->query($sql, $paramsToValue, self::class);
    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    public function update(): void
    {
        // UPDATE tableName SET (col1 = val1, col2 = val2);
        //[val1=>:col1]

        $mappedProperties = $this->mapToDbProperties();
        $params = [];
        $paramsForSet = [];

        foreach ($mappedProperties as $columnName => $value) {
            $param = ':' . $columnName;
            $params[$param] = $value;
            $paramsForSet[] = $columnName . ' = ' . $param;
        }

        $paramsForSql = implode(', ', $paramsForSet);

        $db = Db::getInstance();
        $sql = 'UPDATE ' . self::getTableName() . ' SET ' . $paramsForSql . ' WHERE book_id = :book_id AND author_id = :author_id;';
        $db->query($sql, $params, self::class);
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

    public static function findAllByBookId(int $bookId): ?array
    {
        $db = Db::getInstance();

        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE book_id = :bookId';
        $result = $db->query($sql, [':bookId' => $bookId], self::class);

        return !empty($result) ? $result : null;
    }

    public function delete(): void
    {
        $db = Db::getInstance();

        $sql = 'DELETE FROM ' . self::getTableName() . ' WHERE book_id = :bookId';
        $db->query($sql, [':bookId' => $this->bookId], self::class);
    }
}