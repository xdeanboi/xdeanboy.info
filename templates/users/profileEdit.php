<?php include __DIR__ . '/../header.php' ?>
    <!--Profile Container-->
    <div class="profile container">

        <!--Form for edit profile-->
        <form action="/users/<?= $user->getId() ?>/profile/edit" method="post" name="profileEdit">
            <h2>Профіль <strong><?= $user->getNickname() ?></strong> <span
                        class="<?= $roleClass ?>"><?= $user->getRole() ?></span>
            </h2>

            <!--Main content-->
            <div class="main_bio row">

                <!--Block avatar user start-->
                <div class="user_image col-12 col-md-3">
                    <? if ($user->isAdmin()): ?>
                        <img src="http://avatars.mitosa.net/Ellete/ellete_0067.jpg" alt="User image">
                    <? elseif ($user->isModerator()): ?>
                        <img src="https://dhgf5mcbrms62.cloudfront.net/340015/image-text-e653jT/Uv58Sza-200x200.png"
                             alt="User image">
                    <? else: ?>
                        <img src="https://cn.i.cdn.ti-platform.com/content/479/showpage/ben-10/es/ben10-200x200.png"
                             alt="User image">
                    <? endif; ?>
                </div>
                <!--Block avatar user end-->

                <!--Block main information user start-->
                <div class="main_bio_user col-12 col-md-7">

                    <h4 class="row">
                        <span class="col-12 col-md-2">Ім'я:</span>

                        <div class="col-12 col-md-10">
                            <input type="text" name="name" value="<?= $profile->getName() ?? '' ?>"
                                   placeholder="Ваше імя"
                                   class="form-control" id="main_bio_user_name"></div>
                    </h4>

                    <h6 class="header_bio_user">
                        <select name="header" class="form-control">
                            <option selected><?= $profile->getHeader() ?? 'Вибрати заголовок' ?></option>
                            <option value="Новачок в IT">Новачок в IT</option>
                            <option value="Вже трохи шарю">Вже трохи шарю</option>
                            <option value="Junior Developer">Junior Developer</option>
                            <option value="Middle Developer">Middle Developer</option>
                            <option value="Senior Developer">Senior Developer</option>
                            <option value="Батя IT">Батя Developers</option>
                            <? if ($user->isAdmin()): ?>
                                <option value="Засновник проекту">Засновник проекту</option>
                            <? elseif ($user->isModerator()): ?>
                                <option value="Модератор проекту">Модератор проекту</option>
                            <? elseif ($user->isVIPUser()): ?>
                                <option value="VIP Користувач">VIP Користувач</option>
                                <option value="Благодійник">Благодійник</option>
                                <option value="Задонатив трохи баксів">Задонатив трохи баксів</option>
                            <? endif; ?>
                        </select>
                    </h6>

                    <h6 class="block_country row">
                        <span class="col-12 col-md-2">Країна: </span>
                        <div class="col-12 col-md-10">
                            <input type="text" name="country" value="<?= $profile->getCountry() ?? '' ?>"
                                   placeholder="Ваша країна"
                                   class="form-control" id="main_bio_user_country">
                        </div>
                    </h6>
                    <!--BLock main information user end-->

                    <div class="social row col-12">
                        <div class="col-12"></div>
                        <i class="fab fa-telegram col-md-2"></i>
                        <input type="text" class="form-control com-md-8" name="links[https://t.me/]"
                               value="<?= $socialNicknames['https://t.me/'] ?? $_POST['links']['https://t.me/'] ?>"
                               placeholder="Nickname телеграм">
                        <div class="col-12"></div>
                        <i class="fab fa-instagram col-md-2"></i>
                        <input type="text" class="form-control col-md-8" name="links[https://www.instagram.com/]"
                               value="<?= $socialNicknames['https://www.instagram.com/'] ?? $_POST['links']['https://www.instagram.com/'] ?>"
                               placeholder="Nickname Інстаграм">
                        <div class="col-12"></div>
                        <i class="fa-brands fa-linkedin col-md-2"></i>
                        <input type="text" class="form-control col-md-8" name="links[https://www.linkedin.com/in/]"
                               value="<?= $socialNicknames['https://www.linkedin.com/in/'] ?? $_POST['links']['https://www.linkedin.com/in/'] ?>"
                               placeholder="Nickname Linkedin">
                    </div>

                </div>
                <!--Block main information user start-->

            </div>
            <!--Main content-->

            <!--Block about user and your skills start-->
            <div class="about_skills row">
                <div class="about_user_profile col-12 col-md-6">
                    <h6>Про мене:</h6>
                    <textarea name="about" id="about_me" class="form-control" rows="10"><?= trim($profile->getAbout()) ?? '' ?></textarea>
                </div>
                <div class="skills_user_profile col-12 col-md-5">
                    <h6>Скіли:</h6>
                    <textarea name="skills" id="skills_me" class="form-control" rows="5"><?= trim($profile->getSkills()) ?? '' ?></textarea>
                </div>
            </div>
            <!--Block about user and your skills end-->

            <button type="submit" class="btn">Зберегти</button>

        </form>
        <!--Form for edit profile-->

    </div>
    <!--Profile Container-->
<?php include __DIR__ . '/../footer.php' ?>