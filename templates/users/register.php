<?php include __DIR__ . '/../header.php'?>
    <!--Container start-->
    <div class="container">
        <!--Main content start-->
        <div class="main_content_register col-12">

            <!--SignUp start-->
            <form class="register col-12" method="post" name="register">
                <h2>Реєстрація</h2>

                <!--Error start-->
                <? if (!empty($error)): ?>
                    <div class="div_error_login_or_register">
                        <p><?= $error?></p>
                    </div>
                <? endif; ?>
                <!--Error end-->

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email"
                           placeholder="Введіть свій email" name="email"
                           value="<?= $_POST['email'] ?? ''?>">
                </div>

                <div class="mb-3">
                    <label for="login" class="form-label">Логін</label>
                    <input type="text" class="form-control" id="login"
                           placeholder="Введіть свій логін" name="login"
                           value="<?= $_POST['login'] ?? ''?>">
                </div>

                <div class="mb-3">
                    <label for="password1" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password1"
                           placeholder="Введіть свій пароль" name="password1">
                </div>

                <div class="mb-3">
                    <label for="password2" class="form-label">Повторіть пароль</label>
                    <input type="password" class="form-control" id="password2"
                           placeholder="Повторіть пароль" name="password2">
                </div>

                <button type="submit" class="btn btn-primary">Зареєструвати</button>

            </form>
            <!--SignUp end-->
        </div>
        <!--Main content end-->
    </div>
    <!--Container stop-->
<?php include __DIR__ . '/../footer.php'?>