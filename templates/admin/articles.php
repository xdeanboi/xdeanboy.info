<?php include __DIR__ . '/../header.php' ?>
    <!--Admin container-->
    <div class="admin_articles container">

        <!--Content block-->
        <div class="row">

            <!--Main content block-->
            <div class="admin_articles_main col-8">
                <h2>Статті</h2>

                <!--Offered articles block-->
                <div class="offered_articles">
                    <h3>Запропоновані теми статей</h3>

                    <? if (!empty($articlesOffered)): ?>
                        <h4>Всього статей: <span><?= count($articlesOffered) ?></span></h4>
                    <? endif; ?>

                    <? if (!empty($articlesOffered)): ?>

                        <? foreach ($articlesOffered as $articleOffered): ?>
                            <div>
                                <p class="btns">
                                    <a href="/admin/articles/create">
                                        <button class="btn_edit">Написати статтю</button>
                                    </a>
                                    <a href="/admin/articles/offered/<?= $articleOffered->getId() ?>/delete">
                                        <button class="btn_delete">X</button>
                                    </a>
                                </p>
                                <ul>
                                    <li><span>Розділ</span> <?= $articleOffered->getSection()->getSection() ?></li>
                                    <li><span>Короткий опис</span><em><?= $articleOffered->getTheme() ?></em></li>
                                    <li><span>Запропонував</span> <a
                                                href="/users/<?= $articleOffered->getUser()->getId() ?>/profile"
                                                target="_blank"><?= $articleOffered->getUser()->getNickname() ?></a>
                                    </li>
                                </ul>
                            </div>
                        <? endforeach; ?>

                    <? else: ?>
                        <p class="not_found">Поки нічого не було запропоновано</p>
                    <? endif; ?>
                </div>
                <!--Offered articles block-->

                <!--Search by filter block-->
                <div class="search_by_filter">
                    <h3>Знайти статті</h3>

                    <? if (!empty($errorFilter)): ?>
                        <p class="error"><?= $errorFilter ?></p>
                    <? endif; ?>
                    <form action="/admin/articles" method="post">
                        <ul>
                            <h4 class="filter">Фільтри:</h4>
                            <li class="row">
                                <h5 class="col-4">За останні дні</h5>
                                <input type="text" class="col-2" name="filterByLastDays" placeholder="Днів">
                            </li>
                            <li class="row">
                                <h5 class="col-4">За розділами</h5>
                                <select name="filterBySection" class="col-4">
                                    <option disabled selected>Вибрати розділ</option>
                                    <? foreach ($articlesSection as $articleSection): ?>
                                        <option value="<?= $articleSection->getSection() ?>"><?= $articleSection->getSection() ?></option>
                                    <? endforeach; ?>
                                </select>
                            </li>
                            <li class="row">
                                <h5 class="col-4">За автором</h5>
                                <input type="text" class="col-4" name="filterByAuthor" placeholder="Nickname автора">
                            </li>
                        </ul>

                        <input type="submit" class="submit" value="Знайти">
                    </form>
                </div>
                <!--Search by filter block-->

                <!--Search result by filter-->
                <? if (!empty($filteredArticles)): ?>
                    <div class="search_result">
                        <h3>Результат пошуку:</h3>

                        <!--Article block by search result block-->
                        <? foreach ($filteredArticles as $filterName => $filteredByFilters): ?>
                            <? if (!empty($filteredByFilters)): ?>
                                <? switch ($filterName) {
                                    case 'filteredByLastDays':
                                        $filter = 'За останні дні - ';
                                        break;
                                    case 'filteredBySection':
                                        $filter = 'Розділ';
                                        break;
                                    case 'filteredByAuthor':
                                        $filter = 'Автор';
                                        break;
                                    default:
                                        $filter = 'Невідомий';
                                        break;
                                }
                                ?>

                                <? foreach ($filteredByFilters as $filteredArticle): ?>
                                    <div class="article_block">

                                        <!--Article header-->
                                        <div class="header_article_block">

                                            <strong>Фільтр:
                                                <span class="section"><?= $filter ?> <?= $_POST['filterByLastDays'] ?? null ?></span>
                                            </strong>

                                            <span>
                                            <a href="/articles/<?= $filteredArticle->getId() ?>/edit" class="btn_edit">Редагувати</a>
                                            <a href="articles/<?= $filteredArticle->getId() ?>/delete">
                                                <button class="btn_delete">X</button>
                                            </a>
                                            </span>

                                        </div>
                                        <!--Article header-->

                                        <!--Article main content-->
                                        <h5>
                                            <a href="/articles/<?= $filteredArticle->getId() ?>"
                                               target="_blank"><span
                                                        class="submit"><?= $filteredArticle->getTitle() ?></span></a>
                                        </h5>

                                        <ul>
                                            <li><span class="object_properties_name">Розділ</span><span
                                                        class="object_properties"><?= $filteredArticle->getSection()->getSection() ?></span>
                                            </li>
                                            <li><span class="object_properties_name">Автор</span>
                                                <a href="/users/<?= $filteredArticle->getAuthor()->getId() ?>/profile"
                                                   target="_blank">
                                                    <span class="object_properties author_nickname"><?= $filteredArticle->getAuthor()->getNickname() ?></span>
                                                </a>
                                            </li>
                                            <li><span class="object_properties_name">Створена</span><span
                                                        class="object_properties"><?= $filteredArticle->getCreatedAtAsDate() ?></span>
                                            </li>
                                        </ul>

                                        <p class="properties_text">
                                            <?= mb_substr($filteredArticle->getText(), 0, 150) . '...' ?>
                                        </p>
                                        <!--Article main content-->

                                    </div>
                                <? endforeach; ?>
                            <? else: ?>
                                <p class="not_found">Невідомий фільтр</p>
                            <? endif; ?>
                        <? endforeach; ?>
                        <!--Article block by search result block-->

                    </div>
                <? endif; ?>
                <!--Search result by filter-->

            </div>
            <!--Main content block-->

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
        <!--Content block-->

    </div>
    <!--Article main content-->
<?php include __DIR__ . '/../footer.php' ?>