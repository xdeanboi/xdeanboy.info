<?php include __DIR__ . '/../header.php'?>
    <!--Content start-->
    <div class="container">

        <!--Main content start-->
        <div class="successful_activate">

            <h2>Успішно редаговано!</h2>
            <h4>Данні про книгу <a href="/books/<?= $book->getId()?>"><?= $book->getName()?></a> успішно редаговано.</h4>

        </div>
        <!--Main content end-->

    </div>
    <!--Content stop-->
<?php include __DIR__ . '/../footer.php'?>