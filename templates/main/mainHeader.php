<!doctype html>
<html lang="ua">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/350930385b.js" crossorigin="anonymous"></script>

    <!--Goggle fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,300;1,900&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poiret+One&family=Roboto+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
          rel="stylesheet">

    <!--My styles-->
    <link rel="stylesheet" href="/styles.css">

    <title><?= $title ?? 'Блог' ?></title>
</head>
<body>

<!--Header start-->
<header class="container-fluid">
    <!--Container block-->
    <div class="container">

        <!--Content menu-->
        <div class="row">
            <h1 class="col-3"><a href="/">Блог</a></h1>

            <!--Main content menu-->
            <nav class="main_menu col-6">
                <ul>
                    <li><a href="/">Головна</a></li>
                    <li><a href="/articles">Статті</a></li>
                    <li><a href="/books">Книги</a></li>
                    <li><a href="/video">Відео</a></li>
                    <li><a href="/about-author">Про автора</a></li>
                </ul>
            </nav>
            <!--Main content menu-->

            <!--Sign up, sign out, admin panel buttons-->
            <? if (!empty($thisUser)): ?>
                <div class="user_icon col-3">
                    <a href="/users/<?= $thisUser->getId() ?>/profile"><i class="fa-solid fa-circle-user"></i></a>
                    <? if ($thisUser->isAdmin()): ?>
                        <a href="/admin/statistics" class="btn">Адмінка</a>
                    <? endif; ?>
                    <a href="/login/out"><button type="button" class="btn btn-outline-secondary">Вийти</button></a>
                </div>
            <? else: ?>
                <div class="sign_btn col-3">
                    <a href="/login"><button type="button" class="btn btn-outline-secondary">Увійти</button></a>
                </div>
            <? endif; ?>
            <!--Sign up, sign out, admin panel buttons-->

        </div>
        <!--Content menu-->

    </div>
    <!--Container block-->
</header>
<!--Header end-->