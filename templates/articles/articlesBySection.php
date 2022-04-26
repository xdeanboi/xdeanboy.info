<?php include __DIR__ . '/../header.php' ?>
    <!--Container start-->
    <div class="container">

        <!--Content block-->
        <div class="content row">

            <!--Main content start-->
            <div class="main_content col-12 col-md-8">

                <!--Switch block by section for header page -->
                <div class="section_img">
                    <? switch ($section->getSection()) {
                        case 'Хакінг':?>
                            <img src="https://wallpaperaccess.com/full/3166719.jpg" alt="Image for post">
                            <?break;
                        case 'Новини':?>
                            <img src="https://media.istockphoto.com/photos/abstract-digital-news-concept-picture-id1192070239" alt="Image for post">
                            <?break;
                        case 'Технології':?>
                            <img src="https://wallpaperaccess.com/full/367270.jpg" alt="Image for post">
                            <?break;
                        case 'Лайфхаки':?>
                            <img src="https://media.istockphoto.com/photos/life-hacks-business-concept-white-office-desk-picture-id815637590" alt="Image for post">
                            <?break;
                        case 'PHP':?>
                            <img src="https://wallpaperaccess.com/full/1278191.jpg" alt="Image for post">
                            <?break;
                        default:?>
                            <img src="https://wallpaperaccess.com/full/4674444.jpg" alt="Image for post">
                            <?break;}?>
                </div>
                <!--Switch block by section for header page -->

                <!--Post start-->
                <?php foreach ($articles as $article): ?>
                    <? $sectionByArticle = \xDeanBoy\Models\Articles\ArticlesSection::findOneByColumn('section', $article->getSection()->getSection())?>

                    <a href="/articles/<?= $article->getId() ?>">

                        <div class="post row">

                            <div class="col-12"><span class="section"><?= $article->getSection()->getSection() ?></span></div>

                            <!--Switch block by section for article image-->
                            <div class="post_img col-12 col-md-2">
                                <? switch ($sectionByArticle->getSection()) {
                                    case 'Хакінг':?>
                                        <img src="https://wallpaperaccess.com/full/3166719.jpg" alt="Image for post">
                                        <?break;
                                    case 'Новини':?>
                                        <img src="https://media.istockphoto.com/photos/abstract-digital-news-concept-picture-id1192070239" alt="Image for post">
                                        <?break;
                                    case 'Технології':?>
                                        <img src="https://wallpaperaccess.com/full/367270.jpg" alt="Image for post">
                                        <?break;
                                    case 'Лайфхаки':?>
                                        <img src="https://media.istockphoto.com/photos/life-hacks-business-concept-white-office-desk-picture-id815637590" alt="Image for post">
                                        <?break;
                                    case 'PHP':?>
                                        <img src="https://wallpaperaccess.com/full/1278191.jpg" alt="Image for post">
                                        <?break;
                                    default:?>
                                        <img src="https://wallpaperaccess.com/full/4674444.jpg" alt="Image for post">
                                        <?break;}?>
                            </div>
                            <!--Switch block by section for article image-->

                            <!--Article information block-->
                            <div class="post_text col-12 col-md-8">
                                <h3><?= $article->getTitle() ?></h3>
                                <div class="information_of_post">
                                    <i class="article_author far fa-user"></i><?= $article->getAuthor()->getNickname() ?>
                                    <i class="article_date far fa-calendar"></i><?= $article->getCreatedAtAsDate() ?>
                                </div>
                                <p><?= mb_substr($article->getText(), 0, 150) ?>...</p>
                            </div>
                            <!--Article information block-->

                        </div>
                    </a>
                    <!--Post end-->
                <?php endforeach; ?>

            </div>
            <!--Main content end-->

            <!--Sidebar start-->
            <div class="sidebar col-12 col-md-3">
                <h2>Розділи</h2>
                <div class="sections">
                    <ul>
                        <? foreach ($allSections as $oneSection): ?>
                            <li><a href="/articles/section/<?= $oneSection->getSection()?>"><?=$oneSection->getSection() ?></a></li>
                        <? endforeach; ?>
                    </ul>
                </div>
            </div>
            <!--Sidebar end-->

        </div>
        <!--Content block-->

    </div>
    <!--Container stop-->
<?php include __DIR__ . '/../footer.php' ?>