<?php

namespace xDeanBoy\Controllers;

use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Exceptions\UnauthorizedException;
use xDeanBoy\Models\Books\BookAuthors;
use xDeanBoy\Models\Books\BookCharacteristic;
use xDeanBoy\Models\Books\BookGenre;
use xDeanBoy\Models\Books\BookLanguage;
use xDeanBoy\Models\Books\Books;
use xDeanBoy\Models\Books\BooksAndAuthors;
use xDeanBoy\Models\Users\Profiles;

class BooksController extends AbstractController
{
    /**
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewAll(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $books = Books::findAll();

        if (empty($books)) {
            throw new NotFoundException('Поки не має ніяких книг');
        }

        $authorsByBookId = [];

        foreach ($books as $book) {
            $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
        }

        if ($authorsByBookId === []) {
            throw new NotFoundException('Автори не знайдені');
        }

        $genres = BookGenre::findAll();

        if (empty($genres)) {
            throw new InvalidArgumentException('Розділи книг не знайдені');
        }

        $languages = BookLanguage::findAll();

        if (empty($languages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        $this->view->renderHtml('books/booksAll.php',
            ['title' => 'Книги з програмування',
                'books' => $books,
                'authorsByBookId' => $authorsByBookId,
                'genres' => $genres,
                'languages' => $languages]);
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewBySearch(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $books = Books::findAll();

        if (empty($books)) {
            throw new NotFoundException();
        }

        $genres = BookGenre::findAll();

        if (empty($genres)) {
            throw new InvalidArgumentException('Розділи книг не знайдені');
        }

        $languages = BookLanguage::findAll();

        if (empty($languages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        $authorsByBookId = [];

        $resultSearch = [];

        foreach ($books as $book) {

            preg_match('~^.*' . $_POST['search'] . '.*$~', $book->getName(), $matchesByName);

            if (!empty($matchesByName)) {
                $resultSearch[] = $matchesByName[0];
                $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
            }
        }

        if (!empty($resultSearch)) {
            if ($authorsByBookId === []) {
                throw new NotFoundException('Автори не знайдені');
            }

            $booksByName = [];

            foreach ($resultSearch as $result) {
                $booksByName[] = Books::getByName($result);
            }

            if (!empty($booksByName)) {
                $this->view->renderHtml('books/booksAll.php',
                    ['title' => 'Результат пошуку ' . $_POST['search'],
                        'books' => $booksByName,
                        'authorsByBookId' => $authorsByBookId,
                        'genres' => $genres,
                        'languages' => $languages]);
                return;
            }
        }

        if (empty($resultSearch)) {
            $allAuthorsSurname = BookAuthors::findAllSurnameAuthors();

            if (!empty($allAuthorsSurname)) {
                $resultByAuthorsSurname = preg_grep('~^' . $_POST['search'] . '.*$~', $allAuthorsSurname);

                if (!empty($resultByAuthorsSurname)) {
                    $filteredResult = array_unique($resultByAuthorsSurname);

                    if (!empty($filteredResult)) {
                        foreach ($filteredResult as $resultByAuthorSurname) {
                            $booksByAuthorSurname = Books::getByAuthorsSurname($resultByAuthorSurname);
                            $filteredBooks = array_filter($booksByAuthorSurname);
                        }

                        $resultSearch = array_filter($filteredBooks);
                    }
                }
            }


            $allAuthorsName = BookAuthors::findAllNameAuthors();

            if (!empty($allAuthorsName)) {
                $resultByAuthorsName = preg_grep('~^' . $_POST['search'] . '.*$~', $allAuthorsName);
                $filteredResult = array_unique($resultByAuthorsName, SORT_STRING);

                if (!empty($filteredResult)) {
                    foreach ($filteredResult as $resultByAuthorName) {
                        $booksByAuthorName = Books::getByAuthorsName($resultByAuthorName);

                        if (!empty($booksByAuthorName)) {
                            $filteredBooks = array_filter($booksByAuthorName);
                        }
                    }

                    if (!empty($filteredBooks)) {
                        $resultSearch = $filteredBooks;
                    }
                }
            }

            if (!empty($resultSearch)) {
                $books = [];
                $authorsByBookId = [];
                foreach ($resultSearch as $booksByAuthor) {
                    $books = $booksByAuthor;
                    foreach ($booksByAuthor as $book) {
                        $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
                    }
                }

                $this->view->renderHtml('books/booksAll.php',
                    ['title' => 'Результат пошуку ' . $_POST['search'],
                        'books' => $books,
                        'authorsByBookId' => $authorsByBookId,
                        'genres' => $genres,
                        'languages' => $languages]);
                return;
            }
        }

        $this->view->renderHtml('books/booksAll.php',
            ['title' => 'Результат пошуку ' . $_POST['search'],
                'books' => null,
                'genres' => $genres,
                'languages' => $languages]);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewBook(int $bookId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $book = Books::getById($bookId);

        if (empty($book)) {
            throw new NotFoundException('Книга не знайдена');
        }

        $authors = Books::getAuthorsBook($bookId);

        if (empty($authors)) {
            throw new InvalidArgumentException('Невідомий автор');
        }

        try {
            $authorsFullName = [];
            foreach ($authors as $author) {
                $authorsFullName[$author->getId()] = $author->getFullName();
            }
        } catch (InvalidArgumentException $e) {
            $this->view->renderHtml('books/bookView.php',
                ['title' => 'Книга ' . $bookId,
                    'book' => $book,
                    'error' => $e->getMessage()]);
            return;
        }

        $this->view->renderHtml('books/bookView.php',
            ['title' => 'Книга ' . $bookId,
                'book' => $book,
                'authorsFullName' => $authorsFullName]);
    }

    /**
     * @param int $authorId
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewByAuthor(int $authorId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (empty($authorId)) {
            throw new NotFoundException();
        }

        $author = BookAuthors::getById($authorId);

        if (empty($author)) {
            throw new NotFoundException();
        }

        $books = Books::getByAuthorId($authorId);


        if (empty($books)) {
            throw new NotFoundException();
        }

        $genres = BookGenre::findAll();

        if (empty($genres)) {
            throw new InvalidArgumentException('Розділи книг не знайдені');
        }

        $languages = BookLanguage::findAll();

        if (empty($languages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        $authorsByBookId = [];

        foreach ($books as $book) {
            $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
        }

        if ($authorsByBookId === []) {
            throw new NotFoundException('Автори не знайдені');
        }

        $this->view->renderHtml('books/booksAll.php',
            ['title' => 'Книги ' . $author->getFullName(),
                'books' => $books,
                'authorsByBookId' => $authorsByBookId,
                'genres' => $genres,
                'languages' => $languages]);
    }

    /**
     * @param string $genre
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewByGenre(string $genre): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (empty($genre)) {
            throw new NotFoundException();
        }

        $books = Books::getByGenre($genre);

        if (empty($books)) {
            throw new NotFoundException();
        }

        $genres = BookGenre::findAll();

        if (empty($genres)) {
            throw new InvalidArgumentException('Розділи книг не знайдені');
        }

        $languages = BookLanguage::findAll();

        if (empty($languages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        $authorsByBookId = [];

        foreach ($books as $book) {
            $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
        }

        if ($authorsByBookId === []) {
            throw new NotFoundException('Автори не знайдені');
        }

        $this->view->renderHtml('books/booksAll.php',
            ['title' => 'Книги за розділом ' . $genre,
                'books' => $books,
                'authorsByBookId' => $authorsByBookId,
                'genres' => $genres,
                'languages' => $languages]);
    }

    /**
     * @param string $language
     * @return void
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewByLanguage(string $language): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (empty($language)) {
            throw new NotFoundException();
        }

        $books = Books::getByLanguage($language);

        if (empty($books)) {
            throw new NotFoundException();
        }

        $genres = BookGenre::findAll();

        if (empty($genres)) {
            throw new InvalidArgumentException('Розділи книг не знайдені');
        }

        $languages = BookLanguage::findAll();

        if (empty($languages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        $authorsByBookId = [];

        foreach ($books as $book) {
            $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
        }

        if ($authorsByBookId === []) {
            throw new NotFoundException('Автори не знайдені');
        }

        $this->view->renderHtml('books/booksAll.php',
            ['title' => 'Книги за мовою ' . $language,
                'books' => $books,
                'authorsByBookId' => $authorsByBookId,
                'genres' => $genres,
                'languages' => $languages]);
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function viewByFilter(): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        $genres = BookGenre::findAll();

        if (empty($genres)) {
            throw new InvalidArgumentException('Розділи книг не знайдені');
        }

        $languages = BookLanguage::findAll();

        if (empty($languages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        if (!empty(array_filter($_POST))) {
            $filteredFilter = array_filter($_POST);
            $resultFilterSearch = [];

            // Filter by page
            if (!empty($filteredFilter['page'])) {
                $result = Books::getByPage($filteredFilter['page']);
                if (!empty($result)) {
                    $resultFilterSearch = $result;
                } else {
                    $this->view->renderHtml('books/booksAll.php',
                        ['title' => 'Книги за фільтрами',
                            'books' => null,
                            'genres' => $genres,
                            'languages' => $languages]);
                    return;
                }
            }
            // Filter by page

            // Filter by year
            if (!empty($filteredFilter['year'])) {
                $result = Books::getByYear($filteredFilter['year']);

                if (!empty($result)) {

                    if (!empty($resultFilterSearch)) {

                        $resultsId = [];

                        foreach ($result as $book) {
                            $resultsId[] = $book->getId();
                        }


                        $resultsLastId = [];

                        foreach ($resultFilterSearch as $book) {
                            $resultsLastId[] = $book->getId();
                        }

                        $checkResultsId = array_intersect($resultsLastId, $resultsId);

                        if (empty($checkResultsId)) {
                            $this->view->renderHtml('books/booksAll.php',
                                ['title' => 'Книги за фільтрами',
                                    'books' => null,
                                    'genres' => $genres,
                                    'languages' => $languages]);
                            return;
                        }

                        $checkResult = [];
                        foreach ($checkResultsId as $resultId) {
                            $checkResult[] = Books::getById($resultId);
                        }

                        if (!empty($checkResult)) {
                            $resultFilterSearch = $checkResult;
                        }
                    } else {
                        $resultFilterSearch = $result;
                    }
                } else {
                    $this->view->renderHtml('books/booksAll.php',
                        ['title' => 'Книги за фільтрами',
                            'books' => null,
                            'genres' => $genres,
                            'languages' => $languages]);
                    return;
                }
            }
            // Filter by page

            // Filter by genre
            if (!empty($_POST['genre'])) {
                $result = Books::getByGenre($_POST['genre']);

                if (!empty($result)) {
                    if (!empty($resultFilterSearch)) {

                        $resultsId = [];

                        foreach ($result as $book) {
                            $resultsId[] = $book->getId();
                        }

                        $resultsLastId = [];

                        foreach ($resultFilterSearch as $book) {
                            $resultsLastId[] = $book->getId();
                        }

                        $checkResultsId = array_intersect($resultsLastId, $resultsId);

                        if (empty($checkResultsId)) {
                            $this->view->renderHtml('books/booksAll.php',
                                ['title' => 'Книги за фільтрами',
                                    'books' => null,
                                    'genres' => $genres,
                                    'languages' => $languages]);
                            return;
                        }

                        $checkResult = [];

                        foreach ($checkResultsId as $resultId) {
                            $checkResult[] = Books::getById($resultId);
                        }

                        if (!empty($checkResult)) {
                            $resultFilterSearch = $checkResult;
                        }

                    } else {
                        $resultFilterSearch = $result;
                    }
                } else {
                    $this->view->renderHtml('books/booksAll.php',
                        ['title' => 'Книги за фільтрами',
                            'books' => null,
                            'genres' => $genres,
                            'languages' => $languages]);
                    return;
                }
            }
            // Filter by page

            // Filter by language
            if (!empty($_POST['language'])) {
                $result = Books::getByLanguage($_POST['language']);

                if (!empty($result)) {
                    if (!empty($resultFilterSearch)) {
                        $resultsId = [];

                        foreach ($result as $book) {
                            $resultsId[] = $book->getId();
                        }

                        $resultsLastId = [];

                        foreach ($resultFilterSearch as $book) {
                            $resultsLastId[] = $book->getId();
                        }

                        $checkResultsId = array_intersect($resultsLastId, $resultsId);

                        if (empty($checkResultsId)) {
                            $this->view->renderHtml('books/booksAll.php',
                                ['title' => 'Книги за фільтрами',
                                    'books' => null,
                                    'genres' => $genres,
                                    'languages' => $languages]);
                            return;
                        }

                        $checkResult = [];

                        foreach ($checkResultsId as $resultId) {
                            $checkResult[] = Books::getById($resultId);
                        }

                        if (!empty($checkResult)) {
                            $resultFilterSearch = $checkResult;
                        }
                    } else {
                        $resultFilterSearch = $result;
                    }
                } else {
                    $this->view->renderHtml('books/booksAll.php',
                        ['title' => 'Книги за фільтрами',
                            'books' => null,
                            'genres' => $genres,
                            'languages' => $languages]);
                }
            }
            // Filter by language

            $authorsByBookId = [];

            foreach ($resultFilterSearch as $book) {
                $authorsByBookId[$book->getId()] = Books::getAuthorsBook($book->getId());
            }

            if ($authorsByBookId === []) {
                throw new NotFoundException('Автори не знайдені');
            }

            $this->view->renderHtml('books/booksAll.php',
                ['title' => 'Книги за фільтрами',
                    'books' => $resultFilterSearch,
                    'authorsByBookId' => $authorsByBookId,
                    'genres' => $genres,
                    'languages' => $languages]);
            return;
        }

        $this->view->renderHtml('books/booksAll.php',
            ['title' => 'Книги за фільтрами',
                'books' => null,
                'genres' => $genres,
                'languages' => $languages]);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws ForbiddenException
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function editBook(int $bookId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException();
        }

        $book = Books::getById($bookId);

        if (empty($book)) {
            throw new NotFoundException('Книга не знайдена');
        }

        $authorsByBook = Books::getAuthorsBook($bookId);
        $authorsFullName = [];

        if (!empty($authorsByBook)) {
            foreach ($authorsByBook as $authorByBook) {
                $authorsFullName[] = $authorByBook->getFullName();
            }
        }

        $bookGenres = BookGenre::findAll();

        if (empty($bookGenres)) {
            throw new InvalidArgumentException('Жанри книг не знайдені');
        }

        $bookLanguages = BookLanguage::findAll();

        if (empty($bookLanguages)) {
            throw new InvalidArgumentException('Мови не знайдені');
        }

        if (!empty($_POST)) {
            try {
                $book->edit($_POST);

                header('Location: /books/' . $book->getId() . '/edit/successful', true, 302);
                return;
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('books/bookEdit.php',
                    ['title' => 'Редагування книги ' . $bookId,
                        'error' => $e->getMessage(),
                        'book' => $book,
                        'authorsByBook' => $authorsByBook,
                        'bookGenres' => $bookGenres,
                        'bookLanguages' => $bookLanguages]);
                return;
            }

        }

        $this->view->renderHtml('books/bookEdit.php',
            ['title' => 'Редагування книги ' . $bookId,
                'book' => $book,
                'authorsFullName' => $authorsFullName,
                'bookGenres' => $bookGenres,
                'bookLanguages' => $bookLanguages]);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function editBookSuccessful(int $bookId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException();
        }

        $book = Books::getById($bookId);

        if (empty($book)) {
            throw new NotFoundException();
        }

        $this->view->renderHtml('books/bookEditSuccessful.php',
            ['title' => 'Успішно редаговано',
                'book' => $book]);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function deleteBook(int $bookId): void
    {
        if (empty($this->user)) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException();
        }

        $book = Books::getById($bookId);

        if (empty($book)) {
            throw new NotFoundException('Книгу не знайденео');
        }

        $bookCharacteristic = BookCharacteristic::getById($bookId);

        if (empty($bookCharacteristic)) {
            throw new NotFoundException('Характеристика книги не знайдена');
        }

        $connectionsBookAndAuthors = BooksAndAuthors::findAllByBookId($bookId);

        if (empty($connectionsBookAndAuthors)) {
            throw new NotFoundException('Зв\'язок книги з авторами не знайдений');
        }

        $book->delete();
        $bookCharacteristic->delete();
        foreach ($connectionsBookAndAuthors as $connectionBookAndAuthors) {
            $connectionBookAndAuthors->delete();
        }

        $this->view->renderHtml('books/bookDeleteSuccessful.php',
            ['title' => 'Успішне видалення',
                'bookId' => $bookId]);
    }

    /**
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function addBook(): void
    {
        if(empty($this->user)) {
            throw new UnauthorizedException();
        }

        if(!$this->user->isAdmin()) {
            throw new ForbiddenException();
        }

        $bookGenres = BookGenre::findAll();

        if (empty($bookGenres)) {
            throw new NotFoundException('Жанри книг не знайдені');
        }

        $bookLanguages = BookLanguage::findAll();

        if (empty($bookLanguages)) {
            throw new NotFoundException('Мови книг не знайдені');
        }

        if(!empty($_POST)) {
            try {
                $book = Books::addBook($_POST);

                header('Location: /books/' . $book->getId(), true, 302);
                return;
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('books/bookAdd.php',
                    ['title' => 'Добавити книгу',
                        'error' => $e->getMessage(),
                        'bookGenres' => $bookGenres,
                        'bookLanguages' => $bookLanguages]);
                return;
            }
        }

        $this->view->renderHtml('books/bookAdd.php',
        ['title' => 'Добавити книгу',
            'bookGenres' => $bookGenres,
            'bookLanguages' => $bookLanguages]);
    }
}
