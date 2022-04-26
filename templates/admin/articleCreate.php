<?php include __DIR__ . '/../header.php' ?>
<!--Admin container-->
<div class="admin_articles container">

    <!--Admin main container-->
    <div class="row">

        <!--Main form block-->
        <form action="/admin/articles/create" method="post" name="createArticle"
              class="create_article admin_articles_main col-8">

            <h2>Створення статті</h2>

            <div class="row">

                <? if (!empty($error)):?>
                <p class="error col-12"><?= $error?></p>
                <? endif;?>

                <div class="col-4">

                    <textarea name="title" id="create_article_title" cols="22" rows="3"
                              placeholder=" Титулка статті"><?= trim($_POST['title']) ?? ''?></textarea>

                    <br>

                    <select name="section" id="create_article_section">
                        <option disabled selected>Вибрати розділ</option>
                        <? foreach ($articleSections as $articleSection): ?>
                            <option value="<?= $articleSection->getSection() ?>"><?= $articleSection->getSection() ?></option>
                        <? endforeach; ?>
                    </select>

                </div>

                <textarea name="text" id="create_article_text" cols="50" rows="10" class="col"
                          placeholder="Напишіть статтю"><?= $_POST['text'] ?? '' ?></textarea>
            </div>

            <input type="submit" value="Написати статтю" class="submit">

        </form>
        <!--Main form block-->

        <!--Admin panel block-->
        <div class="admin_panel col-3">
            <h3>Адмін панель</h3>

            <ul>
                <a href="/admin/statistics">
                    <li>Статистика</li>
                </a>
                <a href="/admin/articles">
                    <li>Статті</li>
                </a>
                <a href="/admin/books">
                    <li>Книги</li>
                </a>
                <a href="/admin/video">
                    <li>Відео</li>
                </a>
                <a href="/admin/users">
                    <li>Користувачі</li>
                </a>
            </ul>
        </div>
        <!--Admin panel block-->

    </div>
    <!--Admin main container-->

</div>
<!--Admin container-->
<?php include __DIR__ . '/../footer.php' ?>
