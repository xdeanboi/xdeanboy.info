<?php include __DIR__ . '/../header.php' ?>
    <!--Main Container-->
    <div class="main_container">

        <!--Block content and book_view block-->
        <form class="book_view block_content" action="/books/<?= $book->getId()?>/edit" method="post">

            <h3>Редагування книги</h3>

            <? if (!empty($error)): ?>
                <p class="error"><?= $error ?></p>
            <? endif; ?>

            <!--Main content-->
            <div class="block_row row">
                <div class="col-3">
                    <img src="<?= $book->getImageLink() ?>" alt="Фото книги">
                    <input type="text" value="<?= $_POST['imageLink'] ?? $book->getImageLink()?>" name="imageLink"
                           placeholder="Посилання на фото" class="input_image_link">
                </div>
                

                <!--Block information book-->
                <div class="book_information col-9">
                    <h4><input type="text" value="<?= $_POST['name'] ?? $book->getName()?>" name="name" class="input_header"></h4>

                    <p>
                        Автори - <input type="text" name="authors"
                                        value="<?= $_POST['authors'] ?? implode(', ', $authorsFullName)?>">
                    </p>

                    <p>Сторінок - <input type="text" name="pages" value="<?= $_POST['pages'] ?? $book->getCharacteristic()->getPages()?>"
                                         class="input_number"></p>

                    <p>Рік написання -
                        <input type="text" name="year" value="<?= $_POST['year'] ?? $book->getCharacteristic()->getYear()?>"
                               class="input_number"></p>

                    <p>Жанр -
                        <select name="genre" id="edit_book_genre">
                            <option selected disabled><?= $book->getCharacteristic()->getGenre()?></option>
                            <? foreach ($bookGenres as $bookGenre):?>
                                <option value="<?= $bookGenre->getName()?>"><?= $bookGenre->getName()?></option>
                            <? endforeach;?>
                        </select>
                    </p>

                    <p>Мова -
                        <select name="language" id="edit_book_language">
                            <option selected disabled><?= $book->getCharacteristic()->getLanguage()?></option>
                            <? foreach ($bookLanguages as $bookLanguage):?>
                                <option value="<?= $bookLanguage->getName()?>"><?= $bookLanguage->getName()?></option>
                            <? endforeach;?>
                        </select>
                    </p>

                    <p>
                        Короткий опис:
                        <br>
                        <textarea name="description" id="edit_book_description" rows="10" cols="60"><?= trim($_POST['description'] ?? $book->getDescription())?></textarea>
                    </p>

                    <input type="submit" value="Редагувати" class="submit">
                </div>
                <!--Block information book-->

            </div>
            <!--Main content-->

        </form>
        <!--Block content and book_view block-->

    </div>
    <!--Main Container-->
<?php include __DIR__ . '/../footer.php' ?>