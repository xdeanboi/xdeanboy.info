<?php include __DIR__ . '/../header.php' ?>
<!--Profile container-->
<div class="profile container">

    <!--Block btn profile-->
    <? if ($thisUser->getId() === $user->getId() || $thisUser->isAdmin()): ?>
        <div class="bio_btn">
            <a href="/users/<?= $user->getId() ?>/profile/edit" class="submit">Редагувати</a>
        </div>
    <? endif; ?>
    <!--Block btn profile-->

    <!--Header profile-->
    <h2>Профіль <strong><?= $user->getNickname() ?></strong> <span
                class="<?= $roleClass ?>"><?= $user->getRole() ?></span></h2>
    <!--Header profile-->

    <!--Block main_bio-->
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

            <h4>
                <span>Ім'я:</span>
                <? if (!empty($profile->getName())): ?>
                    <strong><?= $profile->getName() ?></strong>
                <? else: ?>
                    <strong class="not_found_bio">Інформація відсутня</strong>
                <? endif; ?>
            </h4>

            <h6 class="header_bio_user">
                <? if (!empty($profile->getHeader())): ?>
                    <strong><?= $profile->getHeader() ?></strong>
                <? endif; ?>
            </h6>

            <h6>
                <span>Країна: </span>
                <? if (!empty($profile->getCountry())): ?>
                    <strong><?= $profile->getCountry() ?></strong>
                <? else: ?>
                    <strong class="not_found_bio">Інформація відсутня</strong>
                <? endif; ?>
            </h6>
            <!--BLock main information user end-->

            <!--Block for social links start-->
            <? if (!empty($socialLinks)): ?>
                <div class="socialIcon">
                    <? foreach ($socialLinks as $social => $socialLink): ?>
                        <? if ($social === 'https://t.me/'): ?>
                            <a href="<?= $socialLink ?>" target="_blank"><i class="fab fa-telegram"></i></a>
                        <? elseif ($social === 'https://www.instagram.com/'): ?>
                            <a href="<?= $socialLink ?>" target="_blank"><i
                                        class="fab fa-instagram"></i></a>
                        <? elseif ($social === 'https://www.linkedin.com/in/'): ?>
                            <a href="<?= $socialLink ?>" target="_blank"><i
                                        class="fa-brands fa-linkedin"></i></a>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
            <!--Block for social links end-->
        </div>
        <!--Block main information user start-->
    </div>
    <!--Block main_bio-->

    <!--Block about user and your skills start-->
    <div class="about_skills row">

        <div class="about_user_profile col-12 col-md-6">
            <h6>Про мене:</h6>
            <? if (!empty($profile->getAbout())): ?>
                <p><?= $profile->getAbout() ?></p>
            <? else: ?>
                <p class="not_found_bio">Інформація відсутня</p>
            <? endif; ?>
        </div>

        <div class="skills_user_profile col-12 col-md-5">
            <h6>Скіли:</h6>
            <? if (!empty($profile->getSkills())): ?>
                <p><?= $profile->getSkills() ?></p>
            <? else: ?>
                <p class="not_found_bio">Інформація відсутня</p>
            <? endif; ?>
        </div>

    </div>
    <!--Block about user and your skills end-->
</div>
<!--Profile container-->
<?php include __DIR__ . '/../footer.php' ?>
