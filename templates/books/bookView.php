<?php include __DIR__ . '/../header.php' ?>
<!--Main Container-->
    <div class="main_container">

        <!--Block content and book_view block-->
        <div class="book_view block_content">

            <div class="row_btns">
                <a href="/books/<?= $book->getId()?>/edit" class="btn_edit">Редагувати</a>
                <a href="/books/<?= $book->getId()?>/delete" class="btn_delete">X</a>
            </div>

            <!--Header book with name and authors-->
            <h3><?= $book->getName() ?> | <?= implode(', ', $authorsFullName)?></h3>
            <!--Header book with name and authors-->

            <!--Main content-->
            <div class="block_row row">
                <img src="<?= $book->getImageLink() ?>" alt="Фото книги" class="col-3">

                <!--Block information book-->
                <div class="book_information col-9">
                    <p>
                        Автори -
                        <? foreach ($authorsFullName as $authorId => $authorFullName):?>
                            <a href="/books/filter/author/<?= $authorId?>"><?= $authorFullName?></a>
                        <? endforeach;?>
                    </p>

                    <p>Сторінок - <span><?= $book->getCharacteristic()->getPages()?></span></p>

                    <p>Рік написання - <span><?= $book->getCharacteristic()->getYear()?></span></p>

                    <p>Жанр - <a href="/books/filter/genre/<?= $book->getCharacteristic()->getGenre()?>"><?= $book->getCharacteristic()->getGenre()?></a></p>

                    <p>Мова - <a href="/books/filter/language/<?= $book->getCharacteristic()->getLanguage()?>"><?= $book->getCharacteristic()->getLanguage()?></a></p>

                    <p>
                        Короткий опис:
                        <p class="book_description"><?= $book->getDescription()?></p>
                    </p>
                </div>
                <!--Block information book-->

            </div>
            <!--Main content-->

        </div>
        <!--Block content and book_view block-->

    </div>
    <!--Main Container-->
<?php include __DIR__ . '/../footer.php' ?>