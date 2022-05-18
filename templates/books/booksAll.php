<?php include __DIR__ . '/../header.php' ?>
    <!--Main container-->
    <div class="main_container">

        <!--Block row-->
        <div class="block_row row">

            <!--Block content-->
            <div class="block_books block_content col-9">

                <h2 class="col-12"><?= !empty($title) ? $title : 'Книги' ?></h2>

                <!--Form Search-->
                <form action="/books/search" class="block_search" id="search_book" method="post">
                    <input type="text" name="search" value="<?= $_POST['search'] ?? '' ?>"
                           placeholder="Знайти" class="search_input">
                    <button class="search_btn" form="search_book"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <!--Form Search-->

                <!--Block rows for books-->
                <? if (!empty($books)): ?>
                    <div class="block_row row row-cols-5">

                        <!--Block one book-->
                        <? foreach ($books as $book): ?>
                            <div class="col block_book">

                                <a href="/books/<?= $book->getId() ?>"><img src="<?= $book->getImageLink() ?>"
                                                                            alt="Фото книги"></a>

                                <h6 class="book_name"><a href="/books/<?= $book->getId() ?>"><?= $book->getName() ?></a>
                                </h6>

                                <? foreach ($authorsByBookId[$book->getId()] as $authorBook): ?>

                                    <h6 class="book_author"><a
                                                href="/books/filter/author/<?= $authorBook->getId() ?>"><?= $authorBook->getFullName() ?></a>
                                    </h6>

                                <? endforeach; ?>

                                <p><a href="/books/filter/genre/<?= $book->getCharacteristic()->getGenre() ?>"
                                      class="programming_language"><?= $book->getCharacteristic()->getGenre() ?></a></p>
                            </div>
                        <? endforeach; ?>
                        <!--Block one book-->

                    </div>
                <? else: ?>
                    <h2 class="not_found">Нічого не знайдено</h2>
                <? endif; ?>
                <!--Block rows for books-->

            </div>
            <!--Block content-->

            <!--Block sidebar-->
            <? include __DIR__ . '/sidebarBook.php' ?>
            <!--Block sidebar-->

        </div>
        <!--Block row-->

    </div>
    <!--Main container-->
<?php include __DIR__ . '/../footer.php' ?>