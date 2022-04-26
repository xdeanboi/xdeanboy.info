<?php include __DIR__ . '/../header.php' ?>
    <div class="div_error">
        <h2>Помилка доступу</h2>
        <p>Error: <strong><?= !empty($error) ? $error : 'Доступ заборонено'?></strong></p>
    </div>
<?php include __DIR__ . '/../footer.php' ?>
