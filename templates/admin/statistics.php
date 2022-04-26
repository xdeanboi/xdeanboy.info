<?php include __DIR__ . '/../header.php' ?>
<!--Statistics container-->
    <div class="statistics container">

        <!--Content block-->
        <div class="row">

            <!--Main content block-->
            <div class="main col-8">
                <h2>Статистика</h2>


                <div class="first_row row">

                    <!--Articles block-->
                    <div class="col">
                        <h4>Статті</h4>

                        <ul>
                            <li>Загальна кількість: <span><?= $statistics['articles']['count'] ?></span></li>
                            <li>За останню неділю: <span><?= $statistics['articles']['lastWeek'] ?></span></li>
                            <li>За сьогодні: <span><?= $statistics['articles']['lastDay'] ?></span></li>
                            <li>Всього за розділами:
                                <ul>
                                    <? foreach ($articlesSection as $articleSection): ?>
                                        <li><?= $articleSection->getSection() ?>:
                                            <span><?= $statistics['articles']['sections'][$articleSection->getSection()] ?? 0?></span>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!--Articles block-->

                    <!--Books block-->
                    <div class="col">
                        <h4>Книги</h4>

                        <ul>
                            <li>Загальна кількість: <span><?= $statistics['books']['count'] ?? 0 ?></span></li>
                            <li>За останню неділю: <span>!</span></li>
                            <li>За сьогодні: <span>!</span></li>
                            <li>Всього за розділами:
                                <ul>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!--Books block-->

                </div>

                <div class="second_row row">

                    <!--Video block-->
                    <div class="col">
                        <h4>Відео</h4>

                        <ul>
                            <li>Загальна кількість: <span><?= $statistics['video']['count'] ?? 0 ?></span></li>
                            <li>За останню неділю: <span>!</span></li>
                            <li>За сьогодні: <span>!</span></li>
                            <li>Всього за розділами:
                                <ul>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                    <li>SectionName: <span>!</span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!--Video block-->

                    <!--Users block-->
                    <div class="col">
                        <h4>Користувачі</h4>

                        <ul>
                            <li>Загальна кількість: <span><?= $statistics['users']['count'] ?? 0 ?></span></li>
                            <li>За останню неділю: <span><?= $statistics['users']['lastWeek'] ?? 0 ?></span></li>
                            <li>За сьогодні: <span><?= $statistics['users']['lastDay'] ?? 0 ?></span></li>
                            <li>Всього за роллю:
                                <ul>
                                    <? foreach ($roles as $role): ?>
                                        <li><?= $role ?>:
                                            <span><?= $statistics['users']['roles'][$role] ?? 0 ?></span>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!--Users block-->

                </div>

            </div>
            <!--Main content block-->

            <!--Admin panel-->
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
            <!--Admin panel-->

        </div>
        <!--Content block-->

    </div>
    <!--Statistics container-->
<?php include __DIR__ . '/../footer.php' ?>