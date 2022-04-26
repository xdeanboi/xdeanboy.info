<?php include __DIR__ . '/../header.php' ?>
<!--Admin users container-->
    <div class="admin_users container">

        <!--Content block-->
        <div class="row">

            <!--Main content block-->
            <div class="block_main col-8">
                <h2>Користувачі</h2>

                <!--New Users block-->
                <div class="new_users">
                    <h3>Нові користувачі</h3>

                    <? if (!empty($newUsers)): ?>
                        <h4>Всього нових: <span><?= count($newUsers) ?></span></h4>
                    <? endif; ?>

                    <? if (!empty($newUsers)): ?>

                        <? foreach ($newUsers as $newUser): ?>
                            <div class="user_block">
                                <h5>Користувач <a href="/users/<?= $newUser->getId() ?>/profile" target="_blank"><span
                                                class="submit"><?= $newUser->getNickname() ?></span></a></h5>

                                <ul>
                                    <li><span class="object_properties_name">Email</span><span
                                                class="object_properties"><?= $newUser->getEmail() ?></span></li>
                                    <li><span class="object_properties_name">Роль</span><span
                                                class="object_properties"><?= $newUser->getRole() ?></span></li>
                                    <li><span class="object_properties_name">Підтверджений</span><span
                                                class="object_properties"><?= $newUser->getIsConfirmed() ? 'Так' : 'Ні' ?></span>
                                    </li>
                                    <li><span class="object_properties_name">Зареєстрований</span><span
                                                class="object_properties"><?= $newUser->getCreatedAtAsDate() ?></span>
                                    </li>
                                </ul>
                            </div>
                        <? endforeach; ?>

                    <? else: ?>
                        <p class="not_found">Нові користувачі не знайдені</p>
                    <? endif; ?>
                </div>
                <!--New Users block-->

                <!--Search by filter block-->
                <div class="search_by_filter">
                    <h3>Знайти користувача</h3>

                    <!--Error block-->
                    <? if (!empty($errorFilter)): ?>
                        <p class="error"><?= $errorFilter ?></p>
                    <? endif; ?>
                    <!--Error block-->

                    <!--Form block-->
                    <form action="/admin/users" method="post">
                        <ul>
                            <h4 class="filter">Фільтри:</h4>

                            <!--Filter last days-->
                            <li class="row">
                                <h5 class="col-4">За останні дні</h5>
                                <input type="text" class="col-2" name="filterByLastDays" placeholder="Днів">
                            </li>
                            <!--Filter last days-->

                            <!--Filter role-->
                            <li class="row">
                                <h5 class="col-4">Роль</h5>
                                <select name="filterByRole" class="col-4">
                                    <option disabled selected>Вибрати роль</option>
                                    <? foreach ($userRoles as $userRole): ?>
                                        <option value="<?= $userRole->getName() ?>"><?= $userRole->getName() ?></option>
                                    <? endforeach; ?>
                                </select>
                            </li>
                            <!--Filter role-->

                            <!--Filter By Confirmed-->
                            <li class="row">
                                <h5 class="col-4">Підтверджені</h5>
                                <select name="filterByConfirmed" class="col-4">
                                    <option disabled selected>Вибрати так/ні</option>
                                    <option value="<?= true ?>">Так</option>
                                    <option value="<?= false ?>">Ні</option>
                                </select>
                            </li>
                            <!--Filter By Confirmed-->

                            <!--Filter Nickname-->
                            <li class="row">
                                <h5 class="col-4">Nickname</h5>
                                <input type="text" class="col-4" name="filterByNickname"
                                       placeholder="Nickname користувача">
                            </li>
                            <!--Filter Nickname-->

                            <!--Filter Email-->
                            <li class="row">
                                <h5 class="col-4">Email</h5>
                                <input type="text" class="col-4" name="filterByEmail" placeholder="Email користувача">
                            </li>
                            <!--Filter Email-->

                        </ul>

                        <input type="submit" class="submit" value="Знайти">
                    </form>
                    <!--Form block-->

                </div>
                <!--Search by filter block-->

                <!--Search result block-->
                <? if (!empty($usersByFilter)): ?>
                    <div class="search_result">
                        <h3>Результат пошуку</h3>
                        <? foreach ($usersByFilter as $filterName => $usersByFilterName): ?>
                            <? if (!empty($usersByFilterName)): ?>
                                <? switch ($filterName) {
                                    case 'filteredByLastDays':
                                        $filter = 'За останні дні - ';
                                        break;
                                    case 'filteredByRole':
                                        $filter = 'Роль';
                                        break;
                                    case 'filteredByConfirmed':
                                        $filter = 'Підтверджений';
                                        break;
                                    case 'filteredByNickname':
                                        $filter = 'Nickname';
                                        break;
                                    case 'filteredByEmail':
                                        $filter = 'Email';
                                        break;
                                    default:
                                        $filter = 'Невідомий';
                                        break;
                                }
                                ?>
                                <? foreach ($usersByFilterName as $userByFilter): ?>
                                    <div class="user_block">

                                        <p>Фільтр: <span class="section"><?= $filter ?> <?= $_POST['filterByLastDays'] ? $_POST['filterByLastDays'] . '' : null?></span></p>

                                        <h5>Користувач <a href="/users/<?= $userByFilter->getId() ?>/profile"
                                                          target="_blank"><span
                                                        class="submit"><?= $userByFilter->getNickname() ?></span></a>
                                        </h5>

                                        <ul>
                                            <li><span class="object_properties_name">Email</span><span
                                                        class="object_properties"><?= $userByFilter->getEmail() ?></span>
                                            </li>
                                            <li><span class="object_properties_name">Роль</span><span
                                                        class="object_properties"><?= $userByFilter->getRole() ?></span>
                                            </li>
                                            <li><span class="object_properties_name">Підтверджений</span><span
                                                        class="object_properties"><?= $userByFilter->getIsConfirmed() ? 'Так' : 'Ні' ?></span>
                                            </li>
                                            <li><span class="object_properties_name">Зареєстрований</span><span
                                                        class="object_properties"><?= $userByFilter->getCreatedAtAsDate() ?></span>
                                            </li>
                                        </ul>

                                    </div>
                                <? endforeach; ?>
                            <? else: ?>
                                <p class="not_found">Нічого не знайдено</p>
                            <? endif; ?>
                        <? endforeach; ?>
                    </div>
                <? endif; ?>
                <!--Search result block-->

            </div>
            <!--Main content block-->

            <!--Admin panel block-->
            <div class="admin_panel col-3">
                <h3>Адмін панель</h3>

                <ul>
                    <a href="/admin/statistics">
                        <li>Статистика</li>
                    </a>
                    <a href="/admin/articles">
                        <li>Статті</li>
                    </a>
                    <a href="/admin/books">
                        <li>Книги</li>
                    </a>
                    <a href="/admin/video">
                        <li>Відео</li>
                    </a>
                    <a href="/admin/users">
                        <li>Користувачі</li>
                    </a>
                </ul>
            </div>
            <!--Admin panel block-->

        </div>
        <!--content block-->

    </div>
    <!--admin users container-->
<?php include __DIR__ . '/../footer.php' ?>