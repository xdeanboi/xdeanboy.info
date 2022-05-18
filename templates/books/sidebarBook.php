<!--Block sidebar-->
<div class="block_sidebar col-3">
    <h2>Фільтри</h2>

    <form action="/books/filter" method="post" class="filter_name">

        <div>
            <label for="page">Сторінки</label>
            <input type="text" name="page" class="input_pages" value="<?= $_POST['page']?>">
        </div>

        <div>
            <label for="year">Рік</label>
            <input type="text" name="year" class="input_pages" value="<?= $_POST['year']?>">
        </div>

        <div>
            <label for="genre">Розділ</label>
            <select name="genre" id="filter_genre">
                <option selected disabled>Вибрати розділ</option>
                <? foreach ($genres as $genre): ?>
                    <option value="<?= $genre->getName() ?>"><?= $genre->getName() ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div>
            <label for="language">Мова</label>
            <select name="language" id="filter_genre">
                <option selected disabled>Вибрати мову</option>
                <? foreach ($languages as $language): ?>
                    <option value="<?= $language->getName() ?>"><?= $language->getName() ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <input type="submit" value="Знайти" class="submit">

    </form>
</div>
<!--Block sidebar-->