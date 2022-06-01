<?php include __DIR__ . '/../header.php' ?>
    <!--Main Container-->
    <div class="main_container">

        <!--Block content and book_view block-->
        <form class="book_view block_content" action="/books/add" method="post">

            <h3>Додавання книги</h3>

            <!--Main content-->
            <div class="block_row row">

                <!--Block information book-->
                <div class="book_information col-12 row" id="add_book_information">

                    <? if (!empty($error)): ?>
                        <p class="error col-12"><?= $error ?></p>
                    <? endif; ?>



                    <h4 class="col-12" id="add_book_header"><input type="text" value="<?= $_POST['name'] ?? null ?>"
                                                                   name="name" class="input_header"
                                                                   placeholder="Назва книги"></h4>

                    <div class="col-6" id="add_main_information">

                        <p>
                            Автори - <input type="text" name="authors" value="<?= $_POST['authors'] ?? null ?>"
                                            placeholder="ПІ авторів через ,">
                        </p>

                        <p>Сторінок - <input type="text" name="pages" value="<?= $_POST['pages'] ?? null ?>"
                                             class="input_number"></p>

                        <p>Рік написання -
                            <input type="text" name="year" value="<?= $_POST['year'] ?? null ?>"
                                   class="input_number"></p>

                        <p>Жанр -
                            <select name="genre" id="add_book_genre">
                                <option selected disabled>Вибрати жанр</option>
                                <? foreach ($bookGenres as $bookGenre): ?>
                                    <option value="<?= $bookGenre->getName() ?>"><?= $bookGenre->getName() ?></option>
                                <? endforeach; ?>
                            </select>
                        </p>

                        <p>Мова -
                            <select name="language" id="add_book_language">
                                <option selected disabled>Вибрати мову</option>
                                <? foreach ($bookLanguages as $bookLanguage): ?>
                                    <option value="<?= $bookLanguage->getName() ?>"><?= $bookLanguage->getName() ?></option>
                                <? endforeach; ?>
                            </select>
                        </p>

                        <input type="text" value="<?= $_POST['imageLink'] ?? null ?>" name="imageLink"
                               placeholder="Посилання на фото книги" id="add_book_link_img">
                    </div>

                    <div class="col-5">
                        <textarea name="description" id="add_book_description" rows="11" cols="60"
                                      placeholder="Коротко опишіть книгу"><?= trim($_POST['description']) ?? null ?></textarea>

                    </div>

                    <input type="submit" value="Добавити книгу" class="submit">
                </div>
                <!--Block information book-->

            </div>
            <!--Main content-->

        </form>
        <!--Block content and book_view block-->

    </div>
    <!--Main Container-->
<?php include __DIR__ . '/../footer.php' ?>