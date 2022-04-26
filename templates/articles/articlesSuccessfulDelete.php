<?php include __DIR__ . '/../header.php'?>
<!--Container block-->
<div class="block_container container">

    <!--Block main-->
    <div class="block_main">
        <h2>Успішно!</h2>
        <p class="successful_delete">
            Статтю <strong><?= $article->getTitle()?></strong> з розділу <span><?= $article->getSection()->getSection()?></span>
            успішно видалено.
        </p>
        <a href="/admin/articles">Повернутися до статей</a>
    </div>
    <!--Block main-->

</div>
<!--Container block-->
<?php include __DIR__ . '/../footer.php'?>
