<?php include __DIR__ . '/../header.php' ?>
<!--Admin container-->
<div class="admin_articles container">

    <!--Admin main container-->
    <div class="row">

        <!--Main Form block-->
        <form action="/articles/<?= $article->getId() ?>/edit" method="post" name="editArticle"
              class="edit_article admin_articles_main col-8">

            <h2>Редагування статті</h2>

            <? if (!empty($error)): ?>
                <p class="error"><?= $error ?></p>
            <? endif; ?>

            <h3><input type="text" name="title" value="<?= $_POST['title'] ?? $article->getTitle() ?>"></h3>

            <ul>
                <li><span class="section">Автор</span> <a href="/users/<?= $article->getAuthorId() ?>/profile"
                                                          target="_blank"><em><?= $article->getAuthorNickname() ?></em></a>
                </li>
                <li><span class="section">Створена</span> <strong><em><?= $article->getCreatedAt() ?></em></strong></li>
                <li><span class="section">Розділ</span> <select name="section" id="edit_article_section">
                        <option value="<?= $article->getSectionName() ?>" disabled
                                selected><?= $article->getSectionName() ?></option>
                        <? foreach ($articleSections as $articleSection): ?>
                            <option value="<?= $articleSection->getSection() ?>"><?= $articleSection->getSection() ?></option>
                        <? endforeach; ?>
                    </select></li>
            </ul>

            <textarea name="text" id="edit_article_text" cols="50" rows="10"><?= trim($article->getText()) ?? $_POST['text'] ?></textarea>

            <br>
            <input type="submit" class="submit" value="Редагувати">
        </form>
        <!--Main Form block-->

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
