<?php include __DIR__ . '/../header.php' ?>
    <div class="div_error">
        <h2>Not Found Error</h2>
        <p>Error: <strong><?= !empty($error) ? $error : 'Не знайдено' ?></strong></p>
    </div>
<?php include __DIR__ . '/../footer.php' ?>