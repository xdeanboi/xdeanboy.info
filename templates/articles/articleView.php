<?php include __DIR__ . '/../header.php' ?>
<!--Container start-->
<div class="container">

    <!--Content block-->
    <div class="content row">

        <!--Main content start-->
        <div class="main_content col-12 col-md-8">

            <!--Post start-->
            <div class="post row">

                <div class="col-12"><span class="section"><?= $article->getSection()->getSection() ?></span></div>

                <!--Switch block by section image for article-->
                <div class="article_img">
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
                <!--Switch block by section image for article-->

                <!--Article main content-->
                <div class="post_text_article col-12 col-md-12">
                    <h3><?= $article->getTitle() ?></h3>
                    <div class="information_of_post_article ">
                        <i class="article_author far fa-user"></i><strong><a href="/users/<?= $article->getAuthorId()?>/profile" target="_blank"><?= $article->getAuthor()->getNickname() ?></a></strong>
                        <i class="article_date far fa-calendar"></i><?= $article->getCreatedAtAsDate() ?>
                        <? if (!empty($article->getEditAt())):?>
                        <p class="edit_date"><span class="edit_at">Редагована</span> <strong><?= $article->getEditAt()?></strong></p>
                        <? endif;?>
                    </div>
                    <p><?= $article->getText() ?></p>
                </div>
                <!--Article main content-->

            </div>
            <!--Post end-->
        </div>
        <!--Main content end-->

        <!--Sidebar start-->
        <div class="sidebar col-12 col-md-3">
            <h2>Розділи</h2>
            <div class="sections">
                <ul>
                    <? foreach ($sections as $section): ?>
                        <li><a href="/articles/section/<?= $section->getSection() ?>"><?= $section->getSection() ?></a>
                        </li>
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
