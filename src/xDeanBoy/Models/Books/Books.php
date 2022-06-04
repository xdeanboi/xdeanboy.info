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

    public function setAuthorsByFullName(string $authorsFullName): void
    {
        $authorsWithFullName = explode(', ', $authorsFullName);

        $checkAuthors = [];
        $notFoundAuthors = [];

        foreach ($authorsWithFullName as $authorWithFullName) {
            $checkAuthors[] = BookAuthors::getByFullName($authorWithFullName);

            if (empty($checkAuthors)) {
                $notFoundAuthors[] = $authorWithFullName;
            }
        }

        if (!empty($checkAuthors)) {
            foreach ($checkAuthors as $checkAuthor) {
                $connectionBookAndAuthor = new BooksAndAuthors();
                $connectionBookAndAuthor->setAuthorId($checkAuthor->getId());
                $connectionBookAndAuthor->setBookId($this->getId());
                $connectionBookAndAuthor->save();
            }
        } else {
            foreach ($notFoundAuthors as $notFoundAuthor) {
                $newAuthor = new BookAuthors();
                $newAuthor->setFullName($notFoundAuthor);
                $newAuthor->save();

                $connectionBookAndAuthor = new BooksAndAuthors();
                $connectionBookAndAuthor->setAuthorId($newAuthor->getId());
                $connectionBookAndAuthor->setBookId($this->getId());
                $connectionBookAndAuthor->save();
            }
        }
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

    /**
     * @param array $bookFields
     * @return $this
     * @throws NotFoundException
     */
    public function edit(array $bookFields): self
    {
        $characteristic = BookCharacteristic::getById($this->getId());

        if (empty($characteristic)) {
            throw new NotFoundException('Характеристика книги не знайдена');
        }

        if (!empty($bookFields['name'])) {
            $this->setName($bookFields['name']);
        }

        if(!empty($bookFields['authors'])) {
            $this->setAuthorsByFullName($bookFields['authors']);
        }

        if (!empty($bookFields['description'])) {
            $this->setDescription($bookFields['description']);
        }

        $this->save();

        if(!empty($bookFields['pages'])) {
            $characteristic->setPages($bookFields['pages']);
        }

        if(!empty($bookFields['year'])) {
            $characteristic->setYear($bookFields['year']);
        }

        if (!empty($bookFields['genre'])) {
            $characteristic->setGenre($bookFields['genre']);
        }

        $characteristic->save();

        if (!empty($bookFields['imageLink'])) {
            $checkImage = BookImages::getByImageLink($bookFields['imageLink']);

            if (empty($checkImage)) {
                $newImage = new BookImages();
                $newImage->setLink($bookFields['imageLink']);
                $newImage->save();
            }
        }

        return $this;
    }

    /**
     * @param array $bookFields
     * @return static|null
     * @throws InvalidArgumentException
     */
    public static function addBook(array $bookFields): ?self
    {
        if (empty($bookFields['name'])) {
            throw new InvalidArgumentException('Заповніть поле Назва книги');
        }

        if (empty($bookFields['authors'])) {
            throw new InvalidArgumentException('Заповніть поле Автори');
        }

        if (empty($bookFields['pages'])) {
            throw new InvalidArgumentException('Заповніть поле Сторінки');
        }

        if (empty($bookFields['year'])) {
            throw new InvalidArgumentException('Заповніть поле Рік');
        }

        if (empty($bookFields['genre'])) {
            throw new InvalidArgumentException('Виберіть жанр книги');
        }

        if (empty($bookFields['language'])) {
            throw new InvalidArgumentException('Виберіть мову книги');
        }

        if (empty($bookFields['imageLink'])) {
            throw new InvalidArgumentException('Заповніть поле Посилання на фото');
        }

        if (empty($bookFields['description'])) {
            throw new InvalidArgumentException('Заповніть поле Короткий опис');
        }

        //Books properties
        $book = new self();
        $book->setName($bookFields['name']);
        $book->setDescription($bookFields['description']);
        $book->save();

        //BookCharacteristic properties
        $bookCharacteristics = new BookCharacteristic();
        $bookCharacteristics->setPages($bookFields['pages']);
        $bookCharacteristics->setYear($bookFields['year']);
        $bookCharacteristics->setGenre($bookFields['genre']);
        $bookCharacteristics->setLanguage($bookFields['language']);
        $bookCharacteristics->save();

        //BookAuthors properties
        $authorsFullName = explode(',', $bookFields['authors']);

        foreach ($authorsFullName as $authorFullName) {
            [$testSurname, $testName] = explode(' ', $authorFullName);

            if (empty($testSurname) || empty($testName)) {
                throw new InvalidArgumentException('Автор має мати Прізвище та Ім\'я');
            }

            $connectionAuthorWithBook = new BooksAndAuthors();
            $connectionAuthorWithBook->setBookId($book->getId());

            $testAuthor = BookAuthors::getByFullName($authorFullName);

            if (empty($testAuthor)) {
                $bookAuthor = new BookAuthors();
                $bookAuthor->setFullName($authorFullName);
                $bookAuthor->save();

                $connectionAuthorWithBook->setAuthorId($bookAuthor->getId());
                $connectionAuthorWithBook->insert();
            } else {
                $connectionAuthorWithBook->setAuthorId($testAuthor->getId());
                $connectionAuthorWithBook->insert();
            }
        }

        //Book image
        $bookImage = new BookImages();
        $bookImage->setLink($bookFields['imageLink']);
        $bookImage->save();

        return $book;
    }
}