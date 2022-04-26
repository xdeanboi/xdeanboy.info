<?php include __DIR__ . '/../header.php' ?>
    <!--Content start-->
    <div class="container">
        <!--Main content start-->
        <div class="main_content_signUp col-12">

            <!--SignUp start-->
            <form class="sign_up col-12" method="post" name="login">
                <h2>Вхід</h2>

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
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="exampleInputPassword1"
                           placeholder="Введіть свій пароль" name="password">
                </div>

                <button type="submit" class="btn">Увійти</button>
                <br>

                <a href="/register" class="a_btn">Зареєструватися</a>
            </form>
            <!--SignUp end-->

        </div>
        <!--Main content end-->
    </div>
    <!--Content stop-->
<?php include __DIR__ . '/../footer.php' ?>