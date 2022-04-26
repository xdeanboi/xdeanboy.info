<?php include __DIR__ . '/../header.php' ?>
    <!--Container start-->
    <div class="container">

        <div class="content row">

            <!--Main content start-->
            <div class="main_content col-12 col-md-8">

                <!--Article image-->
                <div class="articles_all_img">
                    <img src="https://media.istockphoto.com/photos/blog-concept-woman-blogger-reading-and-writing-online-picture-id1061262194"
                         alt="Image for all articles">
                </div>
                <!--Article image-->

                <!--Post block-->
                <?php foreach ($articles as $article): ?>
                    <? $sectionByArticle = \xDeanBoy\Models\Articles\ArticlesSection::findOneByColumn('section', $article->getSection()->getSection()) ?>
                    <a href="/articles/<?= $article->getId() ?>">

                        <div class="post row">

                            <div class="col-12"><span class="section"><?= $article->getSection()->getSection() ?></span></div>

                            <!--Switch block for image by section article-->
                            <div class="post_img col-12 col-md-2">
                                <? switch ($sectionByArticle->getSection()) {
                                    case 'Хакінг':
                                        ?>
                                        <img src="https://wallpaperaccess.com/full/3166719.jpg" alt="Image for post">
                                        <? break;
                                    case 'Новини':
                                        ?>
                                        <img src="https://media.istockphoto.com/photos/abstract-digital-news-concept-picture-id1192070239"
                                             alt="Image for post">
                                        <? break;
                                    case 'Технології':
                                        ?>
                                        <img src="https://wallpaperaccess.com/full/367270.jpg" alt="Image for post">
                                        <? break;
                                    case 'Лайфхаки':
                                        ?>
                                        <img src="https://media.istockphoto.com/photos/life-hacks-business-concept-white-office-desk-picture-id815637590"
                                             alt="Image for post">
                                        <? break;
                                    case 'PHP':
                                        ?>
                                        <img src="https://wallpaperaccess.com/full/1278191.jpg" alt="Image for post">
                                        <? break;
                                    default:
                                        ?>
                                        <img src="https://wallpaperaccess.com/full/4674444.jpg" alt="Image for post">
                                        <? break;
                                } ?>
                            </div>
                            <!--Switch block for image by section article-->

                            <!--Text block-->
                            <div class="post_text col-12 col-md-9">
                                <h3><?= $article->getTitle() ?></h3>
                                <div class="information_of_post">
                                    <i class="article_author far fa-user"></i> <strong><?= $article->getAuthor()->getNickname() ?></strong>
                                    <i class="article_date far fa-calendar"></i> <?= $article->getCreatedAtAsDate() ?>
                                </div>
                                <p><?= mb_substr($article->getText(), 0, 150) ?>...</p>
                            </div>
                            <!--Text block-->

                        </div>
                    </a>
                <?php endforeach; ?>
                <!--Post end-->

            </div>
            <!--Main content end-->

            <!--Sidebar start-->
            <div class="sidebar col-12 col-md-3">

                <!--Section article-->
                <div class="article_sections">
                    <h2>Розділи</h2>
                    <div class="sections">
                        <ul>
                            <? foreach ($articlesSection as $section): ?>
                                <li>
                                    <a href="/articles/section/<?= $section->getSection() ?>"><?= $section->getSection() ?></a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!--Section article-->

                <!--Offering article-->
                <div class="block_offering">
                    <h2>Запропонувати тему статті</h2>

                    <!--Error block-->
                    <? if (!empty($errorByOffered)): ?>
                        <p><?= $errorByOffered ?></p>
                    <? endif; ?>
                    <!--Error block-->

                    <!--Form to offer article-->
                    <form action="/articles/offering" method="post">

                        <select name="offeringSection">
                            <option disabled selected>Вибрати розділ</option>
                            <? foreach ($articlesSection as $offeringSection): ?>
                                <option value="<?= $offeringSection->getSection() ?>"><?= $offeringSection->getSection() ?></option>
                            <? endforeach; ?>
                        </select>

                        <input type="text" name="offeringTheme" placeholder="Тема статті">

                        <input type="submit" value="Запропонувати" class="submit">
                    </form>
                    <!--Form to offer article-->

                </div>
                <!--Section article-->

            </div>
            <!--Sidebar end-->

        </div>
    </div>
    <!--Content stop-->
<?php include __DIR__ . '/../footer.php' ?>